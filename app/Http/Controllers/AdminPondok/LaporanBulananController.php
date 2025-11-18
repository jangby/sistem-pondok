<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\JenisPembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;

class LaporanBulananController extends Controller
{
    /**
     * Helper: Ambil data utama untuk laporan
     */
    private function getLaporanData(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // --- Ambil Input Filter ---
        $jp_id = $request->input('jenis_pembayaran_id');
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $status_filter = $request->input('status_filter');

        // --- Ambil Data untuk Form Filter ---
        $jenisPembayarans = JenisPembayaran::where('pondok_id', $pondokId)
                                ->where('tipe', 'bulanan')
                                ->orderBy('name')
                                ->get();

        // --- Query Utama ---
        $santrisQuery = Santri::where('pondok_id', $pondokId)
                        ->where('status', 'active');
        
        $laporanData = [];
        $summary = [
            'total_santri' => 0, 'total_lunas' => 0, 'total_belum_lunas' => 0,
            'total_tunggakan' => 0, 'total_pemasukan' => 0, 'total_keringanan' => 0,
        ];

        // Hanya jalankan query jika admin sudah memilih jenis pembayaran
        if ($jp_id) {
            
            // Terapkan filter Jenis Kelamin & Kelas (jika ada)
            $santrisQuery->when($request->filled('jenis_kelamin'), 
                fn($q) => $q->where('jenis_kelamin', $request->jenis_kelamin)
            );
            $santrisQuery->when($request->filled('kelas'), 
                fn($q) => $q->where('kelas_id', $request->kelas)
            );

            // Ambil semua santri aktif, lalu "tempelkan" (load) tagihan
            // yang sesuai dengan filter bulan, tahun, dan jenis pembayaran
            $santris = $santrisQuery->with(['tagihans' => function($query) use ($jp_id, $bulan, $tahun) {
                $query->where('jenis_pembayaran_id', $jp_id)
                      ->where('periode_bulan', $bulan)
                      ->where('periode_tahun', $tahun);
            }])->orderBy('full_name')->get();
            
            // Proses data untuk laporan
            foreach ($santris as $santri) {
                $tagihan = $santri->tagihans->first(); // Ambil 1 tagihan yg cocok
                
                $status = 'Belum Dibuat';
                $nominal_tagihan = 0;
                $tgl_bayar = null;

                if ($tagihan) {
                    $status = $tagihan->status; // pending, paid, partial
                    $nominal_tagihan = $tagihan->nominal_tagihan;
                    $summary['total_keringanan'] += $tagihan->nominal_keringanan;

                    if ($tagihan->status == 'paid') {
                        $summary['total_lunas']++;
                        // Cari tanggal bayar terakhir
                        $tgl_bayar = $tagihan->transaksis()
                                        ->where('status_verifikasi', 'verified')
                                        ->latest('tanggal_bayar')->first()->tanggal_bayar ?? null;
                        $summary['total_pemasukan'] += $tagihan->nominal_tagihan;
                    } else {
                        $summary['total_belum_lunas']++;
                        $summary['total_tunggakan'] += $tagihan->tagihanDetails->sum('sisa_tagihan_item');
                    }
                } else {
                    $summary['total_belum_lunas']++;
                }

                $laporanData[] = [
                    'santri' => $santri,
                    'status' => $status,
                    'nominal_tagihan' => $nominal_tagihan,
                    'tgl_bayar' => $tgl_bayar,
                ];
            }
            
            // Terapkan filter status (Lunas/Belum)
            if ($status_filter) {
                $laporanData = array_filter($laporanData, function($item) use ($status_filter) {
                    if ($status_filter == 'lunas') return $item['status'] == 'paid';
                    if ($status_filter == 'belum_lunas') return $item['status'] != 'paid';
                    return true;
                });
            }
            $summary['total_santri'] = $santris->count();
        }

        return [
            'jenisPembayarans' => $jenisPembayarans,
            'laporanData' => $laporanData,
            'summary' => $summary,
            'filters' => $request->all(), // Untuk PDF
        ];
    }

    /**
     * Halaman Laporan Bulanan (View HTML)
     */
    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        $daftarKelas = Santri::select('kelas_id')->distinct()->whereNotNull('kelas_id')->orderBy('kelas_id')->get();

        return view('adminpondok.laporan-bulanan.index', [
            'jenisPembayarans' => $data['jenisPembayarans'],
            'laporanData' => $data['laporanData'],
            'summary' => $data['summary'],
            'daftarKelas' => $daftarKelas,
        ]);
    }

    /**
     * Halaman Cetak PDF
     */
    public function downloadPDF(Request $request)
    {
        $data = $this->getLaporanData($request);
        
        $jenisPembayaran = \App\Models\JenisPembayaran::find($request->jenis_pembayaran_id);
        $jpNama = $jenisPembayaran->name ?? 'Laporan';

        // 1. Judul untuk TAMPILAN di dalam PDF (Boleh pakai /)
        $judulLaporan = "Laporan {$jpNama} - {$request->bulan}/{$request->tahun}";

        // --- INI PERBAIKANNYA ---
        // 2. Nama file untuk DOWNLOAD (TIDAK BOLEH pakai /)
        // Kita ganti "/" dengan "-"
        $namaFile = "Laporan-{$jpNama}-{$request->bulan}-{$request->tahun}.pdf";
        // -------------------------
        
        $pdf = \App::make('dompdf.wrapper');
        
        // Kirim Judul (yang cantik) ke view
        $pdf->loadView('adminpondok.laporan-bulanan.pdf', [
            'laporanData' => $data['laporanData'],
            'summary' => $data['summary'],
            'judulLaporan' => $judulLaporan, // View tetap pakai judul asli
        ]);
        
        // Gunakan Nama File (yang aman) untuk di-stream/download
        return $pdf->setPaper('a4', 'landscape')->stream($namaFile); 
    }
}