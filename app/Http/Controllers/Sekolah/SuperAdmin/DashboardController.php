<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\Sekolah;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil ID pondok dari Super Admin Sekolah yang sedang login
        $user = Auth::user();
        $pondokId = $user->pondokStaff->pondok_id; //

        // 2. Hitung statistik
        
        // Hitung jumlah unit sekolah (MTS, MA, dll) di pondok ini
        $jumlahSekolah = Sekolah::where('pondok_id', $pondokId)->count(); //

        // Hitung jumlah user dengan role 'admin-sekolah' di pondok ini
        $jumlahAdminSekolah = User::role('admin-sekolah')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
            ->count();

        // Hitung jumlah user dengan role 'guru' di pondok ini
        $jumlahGuru = User::role('guru')
            ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
            ->count();

        // 3. Kirim data ke view
        return view('sekolah.superadmin.dashboard', compact(
            'jumlahSekolah',
            'jumlahAdminSekolah',
            'jumlahGuru'
        ));
    }
}