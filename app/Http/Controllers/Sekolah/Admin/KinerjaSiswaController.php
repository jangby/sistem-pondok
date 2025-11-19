<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\AbsensiSiswaSekolah;
use App\Models\Santri;
use Carbon\Carbon;

class KinerjaSiswaController extends Controller
{
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return $sekolah;
    }
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index(Request $request)
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();
        
        // Filter Bulan
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Ambil Semua Santri di tingkat sekolah ini
        $santris = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->whereHas('kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat))
            ->with('kelas')
            ->get();

        $laporan = [];

        foreach ($santris as $santri) {
            // Ambil Log Absensi Sekolah (Gerbang)
            $logs = AbsensiSiswaSekolah::where('sekolah_id', $sekolah->id)
                        ->where('santri_id', $santri->id)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->get();

            $hadir = $logs->count();
            $terlambat = $logs->where('status_masuk', 'terlambat')->count();
            $tepatWaktu = $hadir - $terlambat;

            // Hitung Skor Kedisiplinan
            // Tepat Waktu = 100 poin, Terlambat = 70 poin.
            // Alpa (tidak hadir) = 0 poin.
            $hariEfektif = 20; // Placeholder
            $poin = ($tepatWaktu * 100) + ($terlambat * 70);
            $skor = min(100, round($poin / ($hariEfektif * 100) * 100));

            $laporan[] = (object) [
                'nama' => $santri->full_name,
                'kelas' => $santri->kelas->nama_kelas,
                'hadir' => $hadir,
                'tepat_waktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'skor' => $skor
            ];
        }

        // Ranking: Skor tertinggi di atas
        usort($laporan, fn($a, $b) => $b->skor <=> $a->skor);

        return view('sekolah.admin.monitoring.kinerja-siswa', compact(
            'laporan', 'bulan', 'tahun'
        ));
    }
}