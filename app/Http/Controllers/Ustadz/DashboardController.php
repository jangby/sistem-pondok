<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ustadz = $user->ustadz; 

        if (!$ustadz) {
            return view('pendidikan.ustadz.no-profile'); 
        }

        // Mapping Hari Indonesia (Manual untuk memastikan)
        $days = [
            'Sunday' => 'Ahad', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $days[\Carbon\Carbon::now()->format('l')];
        
        $jadwalHariIni = $ustadz->jadwals()
                               ->where('hari', $hariIni)
                               ->with(['mustawa', 'mapel'])
                               ->orderBy('jam_mulai')
                               ->get();

        return view('pendidikan.ustadz.dashboard', compact('ustadz', 'jadwalHariIni'));
    }
    
    // --- FITUR PROFIL ---

    public function profil() {
        $user = Auth::user();
        $ustadz = $user->ustadz; // Mengambil data profil ustadz

        return view('pendidikan.ustadz.profil', compact('user', 'ustadz'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $ustadz = $user->ustadz;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'spesialisasi' => 'nullable|string|max:100',
            'password'     => 'nullable|confirmed|min:8', // Opsional ganti password
        ]);

        // 1. Update Data User (Login)
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        // Update nama di tabel user juga agar sinkron
        $user->update(['name' => $request->nama_lengkap]);

        // 2. Update Data Profil Ustadz
        if ($ustadz) {
            $ustadz->update([
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp'        => $request->no_hp,
                'alamat'       => $request->alamat,
                'spesialisasi' => $request->spesialisasi,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}