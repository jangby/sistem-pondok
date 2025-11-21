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
        $pondokId = auth()->user()->pondok_id;

        // Hitung Ringkasan Data
        $totalMustawa = Mustawa::count();
        $totalUstadz = Ustadz::count();
        $totalJadwal = JadwalDiniyah::count();
        
        // Ambil santri yang aktif pondok (asumsi semua santri wajib diniyah)
        $totalSantri = Santri::where('status', 'active')->count();

        return view('pendidikan.admin.dashboard', compact(
            'totalMustawa', 'totalUstadz', 'totalJadwal', 'totalSantri'
        ));
    }
}