<?php

namespace App\Http\Controllers\Sekolah\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComputerLog; 

class ComputerLabController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Petugas Lab
     * Berisi menu-menu dalam bentuk Icon Grid
     */
    public function dashboard()
    {
        // Kita bisa ambil ringkasan data untuk ditampilkan di dashboard
        $totalKomputer = ComputerLog::count();
        $komputerOnline = ComputerLog::where('last_seen', '>=', now()->subMinutes(2))->count();
        $komputerOffline = $totalKomputer - $komputerOnline;

        return view('sekolah.petugas.lab-komputer.dashboard', compact('totalKomputer', 'komputerOnline', 'komputerOffline'));
    }

    /**
     * Halaman List Komputer (Contoh Menu 1)
     */
    public function listKomputer()
    {
        $computers = ComputerLog::orderBy('pc_name', 'asc')->get();
        return view('sekolah.petugas.lab-komputer.list', compact('computers'));
    }
}