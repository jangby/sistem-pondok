<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\AlokasiPembayaran;
use App\Models\Setoran;
use App\Models\User; // <-- Import User
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SetoranController extends Controller
{
    /**
     * Helper: Query dasar (Siap Setor)
     */
    private function getQuerySiapSetor()
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        return PembayaranTransaksi::where('pembayaran_transaksis.pondok_id', $pondokId)
                                    ->where('pembayaran_transaksis.status_verifikasi', 'verified')
                                    ->whereNull('pembayaran_transaksis.setoran_id');
    }

    /**
     * Helper: Query berdasarkan Kategori (permintaan Anda)
     */
    private function getQueryByKategori($kategori)
    {
        $query = $this->getQuerySiapSetor();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        switch ($kategori) {
            case 'tunggakan':
                // Tagihan bulanan TAHUN LALU, ATAU TAHUN INI TAPI BULAN LALU
                return $query->whereHas('tagihan', fn($q) => 
                    $q->where('periode_tahun', '<', $currentYear)
                      ->orWhere(fn($q2) => 
                          $q2->where('periode_tahun', $currentYear)
                             ->where('periode_bulan', '<', $currentMonth)
                      )
                );
            case 'bulan_ini':
                // Tagihan bulanan HANYA TAHUN INI DAN BULAN INI
                return $query->whereHas('tagihan', fn($q) => 
                    $q->where('periode_tahun', $currentYear)
                      ->where('periode_bulan', $currentMonth)
                );
            case 'bulan_depan':
                // Tagihan bulanan TAHUN DEPAN, ATAU TAHUN INI TAPI BULAN DEPAN
                return $query->whereHas('tagihan', fn($q) => 
                    $q->where('periode_tahun', '>', $currentYear)
                      ->orWhere(fn($q2) => 
                          $q2->where('periode_tahun', $currentYear)
                             ->where('periode_bulan', '>', $currentMonth)
                      )
                );
            case 'lain_lain':
                // Tagihan non-bulanan (Tahunan, Sekali Bayar, dll)
                return $query->whereHas('tagihan.jenisPembayaran', fn($q) => 
                    $q->where('tipe', '!=', 'bulanan')
                );
        }
        return $query; // Fallback (seharusnya tidak terjadi)
    }

    /**
     * Halaman Laporan (Report) - GET /setoran
     * (Kita biarkan sama seperti sebelumnya, ini HANYA laporan)
     */
    public function index(Request $request)
    {
        // (Logika fungsi index Anda sebelumnya sudah benar, kita salin lagi)
        $query = $this->getQuerySiapSetor();
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_bayar', '<=', $request->tanggal_selesai);
        }
        
        $transaksiIds = $query->pluck('id');
        $transaksis = $query->with('orangTua', 'tagihan.santri')
                           ->latest('tanggal_bayar')
                           ->paginate(20)->withQueryString();

        $summaryPerItem = AlokasiPembayaran::join('tagihan_details as td', 'alokasi_pembayarans.tagihan_detail_id', '=', 'td.id')
                            ->whereIn('pembayaran_transaksi_id', $transaksiIds)
                            ->groupBy('td.nama_item')
                            ->select('td.nama_item', DB::raw('SUM(alokasi_pembayarans.nominal_alokasi) as total_terkumpul'))
                            ->orderBy('total_terkumpul', 'desc')
                            ->get();
        
        $totalSiapSetor = PembayaranTransaksi::whereIn('id', $transaksiIds)->sum('total_bayar');
        
        return view('adminpondok.setoran.index', compact('transaksis', 'summaryPerItem', 'totalSiapSetor'));
    }

    /**
     * Halaman Form - GET /setoran/create
     * Menampilkan OPSI untuk membuat setoran.
     */
    public function create()
    {
        // Ambil 4 total untuk 4 kategori
        $data = [
            'tunggakan' => $this->getQueryByKategori('tunggakan')->sum('total_bayar'),
            'bulan_ini' => $this->getQueryByKategori('bulan_ini')->sum('total_bayar'),
            'bulan_depan' => $this->getQueryByKategori('bulan_depan')->sum('total_bayar'),
            'lain_lain' => $this->getQueryByKategori('lain_lain')->sum('total_bayar'),
        ];
        
        return view('adminpondok.setoran.create', compact('data'));
    }

    /**
     * Logika Simpan - POST /setoran
     * Memproses berdasarkan kategori yang dipilih.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:tunggakan,bulan_ini,bulan_depan,lain_lain',
            'catatan' => 'nullable|string|max:255',
        ]);
        
        $kategori = $validated['kategori'];

        DB::beginTransaction();
        try {
            // Ambil transaksi HANYA untuk kategori yang dipilih
            $transaksisToLock = $this->getQueryByKategori($kategori)->get(); 
            
            if ($transaksisToLock->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada transaksi terverifikasi untuk kategori tersebut.');
            }

            $totalSetoran = $transaksisToLock->sum('total_bayar');

            // Buat 1 record Setoran baru
            $setoran = Setoran::create([
                'pondok_id' => Auth::user()->pondokStaff->pondok_id,
                'admin_id_penyetor' => Auth::id(),
                'tanggal_setoran' => now(),
                'total_setoran' => $totalSetoran,
                'kategori_setoran' => $kategori, // <-- SIMPAN KATEGORI
                'catatan' => $request->catatan,
            ]);

            // "Kunci" semua transaksi
            $transaksiIds = $transaksisToLock->pluck('id');
            PembayaranTransaksi::whereIn('id', $transaksiIds)->update([
                'setoran_id' => $setoran->id
            ]);
            
            DB::commit();

            return redirect()->route('adminpondok.setoran.history')
                             ->with('success', 'Laporan setoran (Kategori: ' . $kategori . ') berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal buat setoran: ' . (string) $e);
            return redirect()->back()->with('error', 'Gagal membuat laporan setoran.');
        }
    }

    /**
     * Halaman Riwayat - GET /setoran/history
     */
    public function history()
    {
        // (Tidak perlu diubah, tapi tambahkan 'kategori_setoran' di view)
        $setorans = Setoran::with('admin', 'bendaharaPenerima')
                            ->where('admin_id_penyetor', Auth::id())
                            ->latest()
                            ->paginate(15);
        
        return view('adminpondok.setoran.history', compact('setorans'));
    }

    // --- FUNGSI LAPORAN PDF (KITA PINDAHKAN DARI JAWABAN SEBELUMNYA) ---
    
    /**
     * Helper: Ambil data rinci untuk laporan
     */
    private function getLaporanData(Setoran $setoran)
    {
        $transaksiIds = $setoran->transaksi()->pluck('id');

        $summaryPerItem = AlokasiPembayaran::join('tagihan_details as td', 'alokasi_pembayarans.tagihan_detail_id', '=', 'td.id')
                            ->whereIn('pembayaran_transaksi_id', $transaksiIds)
                            ->groupBy('td.nama_item')
                            ->select('td.nama_item', DB::raw('SUM(alokasi_pembayarans.nominal_alokasi) as total_terkumpul'))
                            ->orderBy('total_terkumpul', 'desc')
                            ->get();

        // Query Daftar Transaksi Rinci (untuk PDF)
        $daftarTransaksi = PembayaranTransaksi::with('tagihan.santri', 'tagihan.jenisPembayaran', 'orangTua')
                            ->whereIn('pembayaran_transaksis.id', $transaksiIds)
                            ->get();

        // Pisahkan Putra & Putri
        $santriPutra = $daftarTransaksi->where('tagihan.santri.jenis_kelamin', 'Laki-laki')
                        ->groupBy('tagihan.santri.id') // Grup per santri
                        ->map(fn($group) => [ // Buat ringkasan per santri
                            'nama' => $group->first()->tagihan->santri->full_name,
                            'nis' => $group->first()->tagihan->santri->nis,
                            'total' => $group->sum('total_bayar'),
                            'rincian' => $group // Simpan rincian transaksinya
                        ])->sortBy('nama');

        $santriPutri = $daftarTransaksi->where('tagihan.santri.jenis_kelamin', 'Perempuan')
                        ->groupBy('tagihan.santri.id')
                        ->map(fn($group) => [
                            'nama' => $group->first()->tagihan->santri->full_name,
                            'nis' => $group->first()->tagihan->santri->nis,
                            'total' => $group->sum('total_bayar'),
                            'rincian' => $group
                        ])->sortBy('nama');

        return compact('summaryPerItem', 'santriPutra', 'santriPutri', 'daftarTransaksi');
    }

    /**
     * Halaman Detail Laporan - GET /setoran/{setoran}
     */
    public function show(Setoran $setoran)
    {
        if ($setoran->admin_id_penyetor != Auth::id()) {
            abort(403);
        }
        $data = $this->getLaporanData($setoran);
        
        return view('adminpondok.setoran.show', [
            'setoran' => $setoran,
            'summaryPerItem' => $data['summaryPerItem'],
            'santriPutra' => $data['santriPutra'],
            'santriPutri' => $data['santriPutri'],
            'daftarTransaksi' => $data['daftarTransaksi'], // <-- Kirim data baru
        ]);
    }

    /**
     * Halaman Cetak PDF - GET /setoran/{setoran}/pdf
     */
    public function downloadPDF(Setoran $setoran)
    {
        if ($setoran->admin_id_penyetor != Auth::id()) {
            abort(403);
        }
        $data = $this->getLaporanData($setoran);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('adminpondok.setoran.pdf', [
            'setoran' => $setoran,
            'summaryPerItem' => $data['summaryPerItem'],
            'santriPutra' => $data['santriPutra'],
            'santriPutri' => $data['santriPutri'],
            'daftarTransaksi' => $data['daftarTransaksi'], // <-- Kirim data baru
        ]);
        
        $namaFile = 'Laporan-Setoran-' . $setoran->id . '-' . $setoran->tanggal_setoran . '.pdf';
        return $pdf->stream($namaFile); 
    }
}