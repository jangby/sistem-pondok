<?php

namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// --- MODELS ---
use App\Models\Santri;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Sekolah\AbsensiSiswaSekolah;
use App\Models\Sekolah\AbsensiGuru; // Tambahan untuk Guru
use App\Models\KesehatanSantri;     // Tambahan untuk Sakit Siswa
use App\Models\Perizinan;           // Tambahan untuk Izin Siswa

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil sekolah yang dikelola admin ini
        $sekolah = $user->sekolahs->first();

        if (!$sekolah) {
            return view('sekolah.admin.dashboard', ['error' => 'Anda belum terhubung dengan sekolah manapun.']);
        }

        $pondokId = $sekolah->pondok_id;
        $today = Carbon::now()->format('Y-m-d');

        // ==========================================
        // 1. STATISTIK TOTAL DATA MASTER
        // ==========================================
        
        // Total Siswa Aktif di tingkat sekolah ini
        $totalSiswa = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->whereHas('kelas', function($q) use ($sekolah) {
                $q->where('tingkat', $sekolah->tingkat);
            })->count();

        // Total Guru yang ditugaskan di sekolah ini
        $totalGuru = User::role('guru')
            ->whereHas('sekolahs', function($q) use ($sekolah) {
                $q->where('sekolahs.id', $sekolah->id);
            })->count();

        // Total Kelas
        $totalKelas = Kelas::where('pondok_id', $pondokId)
            ->where('tingkat', $sekolah->tingkat)
            ->count();

        // ==========================================
        // 2. STATISTIK ABSENSI SISWA HARI INI
        // ==========================================

        // Hadir: Hitung data scan masuk hari ini (tepat waktu + terlambat)
        $siswaHadir = AbsensiSiswaSekolah::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->count(); 
            
        // Sakit: Ambil dari tabel KesehatanSantri (UKS) yang relevan dengan sekolah ini
        $siswaSakit = KesehatanSantri::where('status', '!=', 'sembuh')
            ->whereDate('tanggal_sakit', '<=', $today)
            ->where(fn($q) => $q->whereDate('tanggal_sembuh', '>=', $today)->orWhereNull('tanggal_sembuh'))
            ->whereHas('santri.kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat)) // Filter tingkat sekolah
            ->count();

        // Izin: Ambil dari tabel Perizinan
        $siswaIzin = Perizinan::where('status', 'disetujui')
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai_rencana', '>=', $today)
            ->whereHas('santri.kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat)) // Filter tingkat sekolah
            ->count();

        // Alpa: Total Siswa - (Hadir + Sakit + Izin)
        // Note: Ini asumsi kasar, angka pasti baru valid setelah jam sekolah berakhir
        $siswaAlpa = max(0, $totalSiswa - ($siswaHadir + $siswaSakit + $siswaIzin));

        // ==========================================
        // 3. STATISTIK ABSENSI GURU HARI INI (REQUEST BARU)
        // ==========================================

        $guruHadir = AbsensiGuru::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $guruSakit = AbsensiGuru::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'sakit')
            ->count();

        $guruIzin = AbsensiGuru::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->count();
            
        $guruAlpa = AbsensiGuru::where('sekolah_id', $sekolah->id)
            ->whereDate('tanggal', $today)
            ->where('status', 'alpa')
            ->count();

        // ==========================================
        // 4. GRAFIK KEHADIRAN SISWA (7 HARI TERAKHIR)
        // ==========================================
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            // Label: Nama Hari (Senin, Selasa...)
            $chartLabels[] = $date->locale('id')->isoFormat('dddd'); 
            
            // Data: Jumlah scan hadir pada tanggal tersebut
            $chartData[] = AbsensiSiswaSekolah::where('sekolah_id', $sekolah->id)
                ->whereDate('tanggal', $date->format('Y-m-d'))
                ->count();
        }

        return view('sekolah.admin.dashboard', compact(
            'sekolah',
            // Master Data
            'totalSiswa', 'totalGuru', 'totalKelas',
            // Stats Siswa
            'siswaHadir', 'siswaSakit', 'siswaIzin', 'siswaAlpa',
            // Stats Guru
            'guruHadir', 'guruSakit', 'guruIzin', 'guruAlpa',
            // Chart
            'chartLabels', 'chartData'
        ));
    }
}