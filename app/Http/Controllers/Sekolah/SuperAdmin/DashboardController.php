<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\Sekolah;
use App\Models\User;
use App\Models\Santri;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pondokId = $user->pondokStaff->pondok_id;

        // 1. Statistik Utama
        $jumlahSekolah = Sekolah::where('pondok_id', $pondokId)->count();

        $jumlahAdminSekolah = User::role('admin-sekolah')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->count();

        $jumlahGuru = User::role('guru')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
            ->count();

        // 2. Ambil Daftar Sekolah & Hitung Statistik Per Sekolah
        // (Agar dashboard lebih informatif, kita tampilkan detail per unit)
        $sekolahList = Sekolah::where('pondok_id', $pondokId)
            ->orderBy('tingkat') // Urutkan misal: SD, SMP, SMA (tergantung value tingkat)
            ->get()
            ->map(function($sekolah) use ($pondokId) {
                // Hitung Guru per Sekolah
                $sekolah->guru_count = User::role('guru')
                    ->whereHas('sekolahs', fn($q) => $q->where('sekolahs.id', $sekolah->id))
                    ->count();
                
                // Hitung Siswa per Sekolah (Berdasarkan Tingkat Kelas)
                $sekolah->siswa_count = Santri::where('pondok_id', $pondokId)
                    ->where('status', 'active')
                    ->whereHas('kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat))
                    ->count();
                
                return $sekolah;
            });

        return view('sekolah.superadmin.dashboard', compact(
            'jumlahSekolah',
            'jumlahAdminSekolah',
            'jumlahGuru',
            'sekolahList'
        ));
    }
}