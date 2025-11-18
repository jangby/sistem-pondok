<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PayoutController extends Controller
{
    /**
     * Helper: Query dasar pondok
     */
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Helper: Menghitung Total Pemasukan Netto (Midtrans)
     */
    private function getTotalNetto()
    {
        return PembayaranTransaksi::where('pondok_id', $this->getPondokId())
                    ->where('payment_gateway', 'midtrans')
                    ->where('status_verifikasi', 'verified')
                    ->sum(DB::raw('total_bayar - biaya_admin'));
    }

    /**
     * Helper: Menghitung Total Penarikan (berdasarkan status)
     */
    private function getTotalDitarikByStatus($status = 'completed')
    {
        return Payout::where('pondok_id', $this->getPondokId())
                    ->where('status', $status)
                    ->sum('total_amount');
    }

    /**
     * Menampilkan halaman "Dompet Midtrans" (KPI Baru)
     */
    public function index()
    {
        // 1. Total Pemasukan
        $totalNetto = $this->getTotalNetto();
        
        // 2. Total Penarikan Selesai (Completed)
        $totalCompleted = $this->getTotalDitarikByStatus('completed');

        // 3. Total Penarikan Pending (Sedang Diproses)
        $totalPending = $this->getTotalDitarikByStatus('pending');

        // 4. Saldo Tersedia (Pemasukan - Selesai - Pending)
        $saldoTersedia = $totalNetto - $totalCompleted - $totalPending;

        // Ambil riwayat penarikan
        $riwayatPayouts = Payout::where('pondok_id', $this->getPondokId())
                        ->with('adminRequest', 'superadminApprove')
                        ->latest('requested_at')
                        ->paginate(15);
        
        return view('adminpondok.payout.index', compact(
            'totalNetto', 
            'totalCompleted',
            'totalPending',
            'saldoTersedia', 
            'riwayatPayouts'
        ));
    }

    /**
     * Mengajukan permintaan penarikan (membuat Payout)
     * (Fungsi ini sudah benar dari langkah sebelumnya)
     */
    public function store(Request $request)
    {
        // 1. Hitung saldo tersedia saat ini (untuk validasi)
        $totalNetto = $this->getTotalNetto();
        $totalCompleted = $this->getTotalDitarikByStatus('completed');
        $totalPending = $this->getTotalDitarikByStatus('pending');
        $saldoTersedia = $totalNetto - $totalCompleted - $totalPending;

        // 2. Validasi Input
        $validated = $request->validate([
            'jumlah_penarikan' => [
                'required', 'numeric', 'min:10000', 'max:' . $saldoTersedia, 
            ],
            'catatan_admin' => 'nullable|string|max:255',
        ], [
            'jumlah_penarikan.max' => 'Jumlah penarikan tidak boleh melebihi saldo tersedia.'
        ]);

        $jumlahDiminta = (float) $validated['jumlah_penarikan'];

        // 3. Jalankan DB Transaction
        DB::beginTransaction();
        try {
            Payout::create([
                'pondok_id' => $this->getPondokId(),
                'admin_id_request' => Auth::id(),
                'total_amount' => $jumlahDiminta,
                'status' => 'pending',
                'catatan_admin' => $request->catatan_admin,
            ]);
            DB::commit();
            
            return redirect()->route('adminpondok.payout.index')
                             ->with('success', 'Permintaan penarikan dana senilai Rp ' . number_format($jumlahDiminta) . ' telah diajukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat Payout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengajukan penarikan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pengajuan Payout.
     * (Fungsi ini sudah benar dari langkah sebelumnya)
     */
    public function show(Payout $payout)
    {
        if ($payout->pondok_id != $this->getPondokId()) {
            abort(404);
        }
        $payout->load('adminRequest', 'superadminApprove');
        return view('adminpondok.payout.show', compact('payout'));
    }

    /**
     * FUNGSI BARU: Membatalkan permintaan penarikan (oleh Admin Pondok)
     */
    public function destroy(Payout $payout)
    {
        // 1. Keamanan: Cek kepemilikan
        if ($payout->pondok_id != $this->getPondokId()) {
            abort(404);
        }

        // 2. Keamanan: Cek status (Hanya yg 'pending')
        if ($payout->status != 'pending') {
            return redirect()->back()->with('error', 'Gagal! Penarikan ini sudah diproses Super Admin.');
        }

        // 3. Hapus data payout
        $payout->delete();

        return redirect()->route('adminpondok.payout.index')
                         ->with('success', 'Permintaan penarikan berhasil dibatalkan.');
    }
}