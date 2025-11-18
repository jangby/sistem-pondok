<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\AbsensiGuru;
use App\Models\Sekolah\SekolahAbsensiSetting;
use Carbon\Carbon;

class KinerjaGuruController extends Controller
{
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return $sekolah;
    }

    public function index(Request $request)
    {
        $sekolah = $this->getSekolah();
        
        // Filter Bulan (Default: Bulan Ini)
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Ambil Setting Batas Telat
        $settings = SekolahAbsensiSetting::where('sekolah_id', $sekolah->id)->first();
        $batasTelat = $settings->batas_telat ?? '07:00:00';

        // Ambil Semua Guru
        $gurus = $sekolah->users()->whereHas('roles', fn($q) => $q->where('name', 'guru'))->get();

        $laporan = [];

        foreach ($gurus as $guru) {
            // Ambil Log Absensi Bulan Ini
            $logs = AbsensiGuru::where('sekolah_id', $sekolah->id)
                        ->where('guru_user_id', $guru->id)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->get();

            $totalHadir = $logs->where('status', 'hadir')->count();
            $totalSakit = $logs->where('status', 'sakit')->count();
            $totalIzin = $logs->where('status', 'izin')->count();
            
            // Hitung Terlambat
            $totalTerlambat = $logs->where('status', 'hadir')
                                   ->filter(function ($log) use ($batasTelat) {
                                       return $log->jam_masuk > $batasTelat;
                                   })->count();
            
            $totalTepatWaktu = $totalHadir - $totalTerlambat;

            // Hitung Skor Kinerja (Sederhana)
            // Poin: Tepat Waktu (100), Terlambat (80), Sakit/Izin (50), Alpa (0)
            // Asumsi hari kerja sebulan = 20 hari (bisa dikembangkan lebih lanjut dengan menghitung hari efektif)
            $hariKerjaEfektif = 20; // Placeholder
            $poin = ($totalTepatWaktu * 100) + ($totalTerlambat * 80) + ($totalSakit * 50) + ($totalIzin * 50);
            $skorAkhir = min(100, round($poin / ($hariKerjaEfektif * 100) * 100)); 

            $laporan[] = (object) [
                'nama' => $guru->name,
                'nip' => $guru->guru->nip ?? '-',
                'hadir' => $totalHadir,
                'tepat_waktu' => $totalTepatWaktu,
                'terlambat' => $totalTerlambat,
                'sakit' => $totalSakit,
                'izin' => $totalIzin,
                'skor' => $skorAkhir
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($laporan, fn($a, $b) => $b->skor <=> $a->skor);

        return view('sekolah.admin.monitoring.kinerja-guru', compact(
            'laporan', 'bulan', 'tahun'
        ));
    }
}