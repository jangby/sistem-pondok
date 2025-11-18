<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\AlokasiPembayaran;
use App\Models\Setoran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- Jangan lupa import Log

class LaporanSetoranController extends Controller
{
    /**
     * Helper function untuk mengambil query dasar transaksi yang "Siap Setor".
     */
    private function getQuerySiapSetor(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        $query = PembayaranTransaksi::where('pondok_id', $pondokId)
                                    ->where('status_verifikasi', 'verified')
                                    ->whereNull('setoran_id'); // KUNCI: Hanya yang belum disetor

        // Terapkan Filter Tanggal (jika ada)
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_bayar', '<=', $request->tanggal_selesai);
        }
        
        return $query;
    }

    /**
     * Menampilkan halaman Laporan Siap Setor.
     */
    public function index(Request $request)
    {
        // 1. Ambil daftar transaksi yang siap setor (sesuai filter)
        $transaksis = $this->getQuerySiapSetor($request)
                           ->with('orangTua', 'tagihan.santri')
                           ->latest('tanggal_bayar')
                           ->get();

        // 2. Ambil ID transaksi tersebut
        $transaksiIds = $transaksis->pluck('id');

        // 3. Query Laporan Per Item (KUNCI ANDA)
        $summaryPerItem = AlokasiPembayaran::join('tagihan_details as td', 'alokasi_pembayarans.tagihan_detail_id', '=', 'td.id')
                            ->whereIn('pembayaran_transaksi_id', $transaksiIds)
                            ->groupBy('td.nama_item')
                            ->select('td.nama_item', DB::raw('SUM(alokasi_pembayarans.nominal_alokasi) as total_terkumpul'))
                            ->orderBy('total_terkumpul', 'desc')
                            ->get();
        
        // 4. Hitung Total
        $totalSiapSetor = $transaksis->sum('total_bayar');
        
        return view('adminpondok.laporan.index', compact('transaksis', 'summaryPerItem', 'totalSiapSetor'));
    }

    /**
     * Proses "Buat Laporan Setoran" (mengunci transaksi).
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Ambil SEMUA transaksi yang lolos filter (sama seperti di index)
            $transaksisToLock = $this->getQuerySiapSetor($request)->get();
            
            if ($transaksisToLock->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada transaksi terverifikasi untuk disetor.');
            }

            // 2. Hitung total
            $totalSetoran = $transaksisToLock->sum('total_bayar');

            // 3. Buat 1 record Setoran baru
            $setoran = Setoran::create([
                'pondok_id' => Auth::user()->pondokStaff->pondok_id,
                'admin_id_penyetor' => Auth::id(),
                'tanggal_setoran' => now(),
                'total_setoran' => $totalSetoran,
                'catatan' => $request->catatan, // Ambil dari form
            ]);

            // 4. "Kunci" semua transaksi dengan setoran_id baru
            $transaksiIds = $transaksisToLock->pluck('id');
            PembayaranTransaksi::whereIn('id', $transaksiIds)->update([
                'setoran_id' => $setoran->id
            ]);
            
            DB::commit();

            return redirect()->route('adminpondok.laporan.history') // Arahkan ke history
                             ->with('success', 'Laporan setoran berhasil dibuat dengan total Rp ' . number_format($totalSetoran, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat setoran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat laporan setoran. ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat setoran yang sudah dibuat.
     */
    public function history()
    {
        $setorans = Setoran::with('admin', 'bendaharaPenerima')
                            ->where('admin_id_penyetor', Auth::id())
                            ->latest()
                            ->paginate(15);
        
        // Kita akan buat view ini di Bagian 1 (Riwayat Admin)
        return view('adminpondok.laporan.history', compact('setorans'));
    }
}