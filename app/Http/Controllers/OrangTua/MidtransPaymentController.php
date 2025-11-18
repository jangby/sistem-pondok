<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\PembayaranTransaksi; // Pastikan ini di-import
use Illuminate\Support\Facades\Auth;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log untuk error
use Illuminate\Support\Str;

class MidtransPaymentController extends Controller
{
    /**
     * Helper function untuk memverifikasi kepemilikan tagihan.
     */
    private function checkOwnership(Tagihan $tagihan)
    {
        $orangTua = Auth::user()->orangTua;
        $santriIds = $orangTua->santris->pluck('id');
        
        // Jika santri_id tagihan ini tidak ada di daftar anak si orang tua,
        // maka lempar error 404 (tidak ditemukan)
        if (!$santriIds->contains($tagihan->santri_id)) {
            abort(404, 'Tagihan tidak ditemukan');
        }
        return $orangTua; // Kembalikan $orangTua agar bisa dipakai
    }

    /**
     * Halaman untuk memilih metode bayar (custom box).
     * URL: GET /orangtua/tagihan/{tagihan}/bayar
     */
    public function pilihMetode(Tagihan $tagihan)
    {
        // 1. Cek Keamanan
        $this->checkOwnership($tagihan);

        // 2. Load detail untuk menghitung sisa
        $tagihan->load('tagihanDetails');
        $sisaTagihan = $tagihan->tagihanDetails->sum('sisa_tagihan_item');

        // 3. Cek Status
        if ($sisaTagihan <= 0) {
            return redirect()->route('orangtua.tagihan.show', $tagihan->id)
                             ->with('error', 'Tagihan ini sudah lunas.');
        }
        
        // 4. Tampilkan view (Halaman Pilihan)
        return view('orangtua.payment.pilih-metode', compact('tagihan', 'sisaTagihan'));
    }

    /**
     * Memproses permintaan pembayaran ke Midtrans.
     * URL: POST /orangtua/tagihan/{tagihan}/bayar
     */
    public function prosesPembayaran(Request $request, Tagihan $tagihan, MidtransService $midtransService)
    {
        // 1. Validasi & Cek Keamanan
        $orangTua = $this->checkOwnership($tagihan);
        
        $tagihan->load('tagihanDetails', 'santri.user');
        $sisaTagihan = $tagihan->tagihanDetails->sum('sisa_tagihan_item');

        if ($sisaTagihan <= 0) {
            return redirect()->route('orangtua.tagihan.show', $tagihan->id)
                             ->with('error', 'Tagihan ini sudah lunas saat Anda memproses.');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|in:bca_va,bni_va,mandiri_bill,qris,gopay',
            'nominal_bayar' => [
                'required', 'numeric', 'min:10000',
                'max:' . $sisaTagihan,
            ],
        ]);
        
        $nominalBayar = (int) $validated['nominal_bayar']; // Nominal untuk tagihan
        $biayaAdmin = 5000; // Biaya admin 5k
        $totalTagihanMidtrans = $nominalBayar + $biayaAdmin; // Total yang dikirim ke Midtrans
        
        $ourOrderId = 'PONDOK-' . $tagihan->pondok_id . '-T' . $tagihan->id . '-' . time();
        $paymentMethod = $request->payment_method;

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
                    'id' => 'TAGIHAN-'.$tagihan->id,
                    'price' => $nominalBayar,
                    'quantity' => 1,
                    'name' => 'Pembayaran ' . $tagihan->jenisPembayaran->name,
                ],
                [
                    'id' => 'ADMIN-FEE',
                    'price' => $biayaAdmin,
                    'quantity' => 1,
                    'name' => 'Biaya Administrasi Transaksi',
                ]
            ],
        ];

        // 3. Sesuaikan Parameter API berdasarkan Metode Bayar
        $metodeDb = 'lainnya';
        switch ($paymentMethod) {
            case 'bca_va':
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => 'bca'];
                $metodeDb = 'virtual_account';
                break;
            case 'bni_va':
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => 'bni'];
                $metodeDb = 'virtual_account';
                break;
            case 'mandiri_bill':
                $params['payment_type'] = 'echannel';
                $params['echannel'] = [
                    'bill_info1' => 'PEMBAYARAN TAGIHAN PONDOK',
                    'bill_info2' => 'A/N: ' . $tagihan->santri->full_name,
                ];
                $metodeDb = 'virtual_account';
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

        // 4. Panggil Service Midtrans
        $midtransResponse = $midtransService->createTransaction($params);

        if (!$midtransResponse) {
            return redirect()->back()->with('error', 'Gagal terhubung dengan gateway pembayaran. Silakan coba lagi.');
        }

        // 5. Simpan Transaksi PENDING ke Database Kita
        DB::beginTransaction();
        try {
            
            // --- INI ADALAH PERBAIKANNYA ---
            // Inisialisasi variabel $paymentDetails sebagai string kosong
            $paymentDetails = ''; 
            // ---------------------------------
            
            // Ambil data VA/QR dari respon
            if (isset($midtransResponse->va_numbers)) { // BCA / BNI
                $paymentDetails = $midtransResponse->va_numbers[0]->va_number;
            } elseif (isset($midtransResponse->bill_key)) { // Mandiri
                $paymentDetails = $midtransResponse->biller_code . '|' . $midtransResponse->bill_key;
            } elseif (isset($midtransResponse->actions[0]->url) && $paymentMethod == 'qris') { // QRIS
                $paymentDetails = $midtransResponse->actions[0]->url; // URL Gambar QR
            } elseif (isset($midtransResponse->actions[1]->url) && $paymentMethod == 'gopay') { // Gopay
                $paymentDetails = $midtransResponse->actions[1]->url; // URL Deep link
            }

            $transaksi = PembayaranTransaksi::create([
                'pondok_id' => $tagihan->pondok_id,
                'orang_tua_id' => $orangTua->id,
                'tagihan_id' => $tagihan->id,
                'midtrans_order_id' => $midtransResponse->order_id,
                'order_id_pondok' => $ourOrderId,
                'total_bayar' => $totalTagihanMidtrans, // Simpan total + admin
                'biaya_admin' => $biayaAdmin, // Simpan biaya admin
                'metode_pembayaran' => $metodeDb,
                'payment_gateway' => 'midtrans',
                'status_verifikasi' => 'pending',
                'bukti_bayar_url' => $paymentDetails, // $paymentDetails dijamin ada
                'catatan_verifikasi' => 'Menunggu pembayaran Midtrans',
            ]);
            
            DB::commit();

            // 6. Redirect ke Halaman Instruksi
            return redirect()->route('orangtua.pembayaran.instruksi', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan transaksi Midtrans: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan internal. Silakan hubungi admin.');
        }
    }

    /**
     * Halaman instruksi (menampilkan VA/QR).
     * URL: GET /orangtua/pembayaran/{transaksi}/instruksi
     */
    public function instruksiPembayaran($transaksiId)
    {
        // 1. Ambil data transaksi
        $transaksi = PembayaranTransaksi::findOrFail($transaksiId);

        // 2. Cek Keamanan
        $orangTua = Auth::user()->orangTua;
        if ($transaksi->orang_tua_id !== $orangTua->id) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        // 3. Cek Status
        if ($transaksi->status_verifikasi != 'pending') {
             return redirect()->route('orangtua.tagihan.show', $transaksi->tagihan_id)
                             ->with('success', 'Pembayaran untuk tagihan ini sudah dikonfirmasi.');
        }
        
        // 4. Kirim data ke view
        return view('orangtua.payment.instruksi', compact('transaksi'));
    }

    /**
     * Endpoint untuk 'polling' status transaksi.
     * URL: GET /orangtua/pembayaran/{transaksi}/status
     */
    public function checkStatus(PembayaranTransaksi $transaksi)
    {
        // 1. Cek Keamanan
        // Pastikan yang bertanya adalah pemilik transaksi
        $orangTua = Auth::user()->orangTua;
        if ($transaksi->orang_tua_id !== $orangTua->id) {
            return response()->json(['status' => 'error', 'message' => 'Not Found'], 404);
        }

        // 2. Kirim status terbaru (pending, verified, rejected, canceled)
        return response()->json([
            'status' => $transaksi->status_verifikasi
        ]);
    }
}