<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Ustadz;
use App\Models\JadwalDiniyah;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pondokId = $user->pondokStaff?->pondok_id ?? $user->pondok_id;

        abort_unless($pondokId, 403, 'Akun pendidikan tidak terhubung dengan pondok.');

        $totalMustawa = Mustawa::where('pondok_id', $pondokId)->count();
        $totalUstadz = Ustadz::where('pondok_id', $pondokId)->count();
        $totalJadwal = JadwalDiniyah::where('pondok_id', $pondokId)->count();
        
        $totalSantri = Santri::where('pondok_id', $pondokId)
            ->where('status', 'active')
            ->count();

        return view('pendidikan.admin.dashboard', compact(
            'totalMustawa', 'totalUstadz', 'totalJadwal', 'totalSantri'
        ));
    }
}