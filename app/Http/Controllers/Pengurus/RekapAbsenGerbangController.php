<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiGerbang;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Package PDF bawaan Laravel

class RekapAbsenGerbangController extends Controller
{
    // Mengambil data untuk halaman Web
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $rekap = $this->getRekapData($bulan, $tahun);
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');

        return view('pengurus.absensi.gerbang.rekap', compact('rekap', 'bulan', 'tahun', 'namaBulan'));
    }

    // Mengubah data menjadi File PDF untuk didownload
    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $rekap = $this->getRekapData($bulan, $tahun);
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');

        $pdf = Pdf::loadView('pengurus.absensi.gerbang.pdf', compact('rekap', 'namaBulan'));
        
        // Atur ukuran kertas ke A4 (Portrait)
        $pdf->setPaper('A4', 'portrait'); 

        return $pdf->download('Laporan_Kinerja_Gerbang_' . $namaBulan . '.pdf');
    }

    // Fungsi bantuan untuk menghitung jumlah absen per santri
    private function getRekapData($bulan, $tahun)
    {
        $absensi = AbsensiGerbang::with('santri')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $rekap = [];
        foreach ($absensi as $absen) {
            $santriId = $absen->santri_id;
            if (!isset($rekap[$santriId])) {
                $rekap[$santriId] = [
                    'nama' => $absen->santri->full_name,
                    'hadir_pagi' => 0,
                    'hadir_sore' => 0,
                    'total_tugas' => 0,
                ];
            }
            if ($absen->absen_pagi) $rekap[$santriId]['hadir_pagi']++;
            if ($absen->absen_sore) $rekap[$santriId]['hadir_sore']++;
            
            $rekap[$santriId]['total_tugas']++;
        }

        // Urutkan berdasarkan nama abjad A-Z
        usort($rekap, function($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        return $rekap;
    }
}