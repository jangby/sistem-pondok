<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dompet;
use App\Models\PembayaranTransaksi;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\OrangTua;
use App\Models\Santri;

class DompetController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Halaman utama Uang Jajan (Saldo, Log, Pengaturan)
     */
    public function index()
    {
        $orangTua = Auth::user()->orangTua;
        // Ambil santri beserta dompetnya
        $santris = $orangTua->santris()->with('dompet')->get();
        
        // (Kita akan lengkapi halaman ini nanti)
        return view('orangtua.dompet.index', compact('santris'));
    }

    /**
     * Tampilkan form untuk Top-up
     */
    public function createTopup()
    {
        $orangTua = Auth::user()->orangTua;
        // Ambil HANYA santri yang sudah punya dompet aktif
        $santris = $orangTua->santris()->whereHas('dompet', fn($q) => $q->where('status', 'active'))
                            ->get();
        
        if ($santris->isEmpty()) {
            return redirect()->route('orangtua.dompet.index')->with('error', 'Tidak ada santri dengan dompet aktif.');
        }

        return view('orangtua.dompet.topup-create', compact('santris'));
    }

    /**
     * Proses Top-up ke Midtrans
     */
    public function storeTopup(Request $request)
    {
        $orangTua = Auth::user()->orangTua;
        
        $validated = $request->validate([
            'dompet_id' => 'required|exists:dompets,id',
            'nominal_topup' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:bca_va,bni_va,mandiri_bill,qris,gopay',
        ]);

        $dompet = Dompet::find($validated['dompet_id']);
        
        if ($dompet->pondok_id != $orangTua->pondok_id || $dompet->santri->orang_tua_id != $orangTua->id) {
            abort(403, 'Akses ditolak.');
        }

        $nominalTopup = (int) $validated['nominal_topup'];
        $biayaAdmin = 5000;
        $totalTagihanMidtrans = $nominalTopup + $biayaAdmin;
        $ourOrderId = 'TOPUP-' . $dompet->pondok_id . '-D' . $dompet->id . '-' . time();
        $paymentMethod = $validated['payment_method']; // misal: 'bni_va'

        DB::beginTransaction();
        try {
            
            // ... (Siapkan $params untuk Midtrans) ...
            $params = [
                'transaction_details' => [
                    'order_id' => $ourOrderId,
                    'gross_amount' => $totalTagihanMidtrans,
                ],
                'customer_details' => [
                    'first_name' => $orangTua->name,
                    'email' => $orangTua->user->email,
                    'phone' => $orangTua->phone,
                ],
                'item_details' => [
                    [
                        'id' => 'TOPUP-'.$dompet->id,
                        'price' => $nominalTopup,
                        'quantity' => 1,
                        'name' => 'Top-up Saldo Uang Jajan A/N ' . $dompet->santri->full_name,
                    ],
                    ['id' => 'ADMIN-FEE', 'price' => $biayaAdmin, 'quantity' => 1, 'name' => 'Biaya Administrasi']
                ],
            ];
            
            // --- INI BLOK PERBAIKANNYA ---
            // Kita terjemahkan 'bni_va' -> 'virtual_account'
            $metodeDb = 'lainnya'; // Default
            switch ($paymentMethod) {
                case 'bca_va':
                case 'bni_va':
                case 'mandiri_bill':
                    $params['payment_type'] = ($paymentMethod == 'mandiri_bill') ? 'echannel' : 'bank_transfer';
                    if($paymentMethod != 'mandiri_bill') {
                        $params['bank_transfer'] = ['bank' => str_replace('_va', '', $paymentMethod)];
                    } else {
                        $params['echannel'] = ['bill_info1' => 'TOP-UP SALDO', 'bill_info2' => 'A/N: ' . $dompet->santri->full_name];
                    }
                    $metodeDb = 'virtual_account'; // <-- Ini kuncinya
                    break;
                case 'qris':
                    $params['payment_type'] = 'qris';
                    $metodeDb = 'qris';
                    break;
                case 'gopay':
                    $params['payment_type'] = 'gopay';
                    $metodeDb = 'gopay';
                    break;
            }

            // 1. Panggil Midtrans
            $midtransResponse = $this->midtransService->createTransaction($params);

            if (!$midtransResponse) {
                throw new \Exception('Gagal terhubung dengan gateway pembayaran.');
            }
            
            // ... (Ambil $paymentDetails) ...
            $paymentDetails = '';
            if (isset($midtransResponse->va_numbers)) { $paymentDetails = $midtransResponse->va_numbers[0]->va_number; }
            elseif (isset($midtransResponse->bill_key)) { $paymentDetails = $midtransResponse->biller_code . '|' . $midtransResponse->bill_key; }
            elseif (isset($midtransResponse->actions[0]->url) && $paymentMethod == 'qris') { $paymentDetails = $midtransResponse->actions[0]->url; }
            elseif (isset($midtransResponse->actions[1]->url) && $paymentMethod == 'gopay') { $paymentDetails = $midtransResponse->actions[1]->url; }


            // 2. Buat Transaksi PENDING (Gunakan $metodeDb)
            $transaksi = PembayaranTransaksi::create([
                'pondok_id' => $dompet->pondok_id,
                'orang_tua_id' => $orangTua->id,
                'tagihan_id' => null,
                'dompet_id' => $dompet->id,
                'midtrans_order_id' => $midtransResponse->order_id,
                'order_id_pondok' => $ourOrderId,
                'total_bayar' => $totalTagihanMidtrans,
                'biaya_admin' => $biayaAdmin,
                'metode_pembayaran' => $metodeDb, // <-- Gunakan nilai yg sudah diterjemahkan
                'payment_gateway' => 'midtrans',
                'status_verifikasi' => 'pending',
                'bukti_bayar_url' => $paymentDetails,
                'catatan_verifikasi' => 'Menunggu top-up Midtrans',
            ]);
            
            DB::commit();

            // 3. Redirect ke Halaman Instruksi
            return redirect()->route('orangtua.pembayaran.instruksi', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Top-up: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * BARU: Update Pengaturan Kartu (Limit & Blokir)
     */
    public function update(Request $request, Dompet $dompet)
    {
        // 1. Validasi Keamanan: Pastikan dompet ini milik anak dari user yang login
        $user = Auth::user();
        $orangTua = OrangTua::where('user_id', $user->id)->first();
        $santri = $dompet->santri;

        if (!$orangTua || $santri->orang_tua_id != $orangTua->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke dompet ini.');
        }

        // 2. Validasi Input
        $validated = $request->validate([
            'daily_spending_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,blocked',
        ]);

        // 3. Simpan Perubahan
        $dompet->update([
            'daily_spending_limit' => $validated['daily_spending_limit'],
            'status' => $validated['status'],
        ]);

        $pesanStatus = ($validated['status'] == 'blocked') ? 'Kartu berhasil diblokir.' : 'Kartu aktif kembali.';
        
        return back()->with('success', 'Pengaturan berhasil disimpan. ' . $pesanStatus);
    }
}