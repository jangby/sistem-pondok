<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ustadz = $user->ustadz; 

        if (!$ustadz) {
            // Jika user punya role ustadz tapi data profil belum dibuat admin
            return view('pendidikan.ustadz.no-profile'); 
        }

        // Mapping Hari Indonesia
        $days = [
            'Sunday' => 'Ahad', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $days[Carbon::now()->format('l')];
        
        $jadwalHariIni = $ustadz->jadwals()
                               ->where('hari', $hariIni)
                               ->with(['mustawa', 'mapel'])
                               ->orderBy('jam_mulai')
                               ->get();

        return view('pendidikan.ustadz.dashboard', compact('ustadz', 'jadwalHariIni'));
    }
    
    public function profil() {
        return view('pendidikan.ustadz.profil');
    }
}