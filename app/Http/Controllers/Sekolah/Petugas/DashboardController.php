<?php

namespace App\Http\Controllers\Sekolah\Petugas;

use App\Http\Controllers\Controller;
// Pastikan namespace Model benar sesuai struktur projek Anda
use App\Models\Perpus\Buku;       
use App\Models\Perpus\Peminjaman; 
use App\Models\Perpus\Kunjungan;  
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung data ringkas untuk dashboard mobile
        $totalBuku = Buku::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $kunjunganHariIni = Kunjungan::whereDate('created_at', Carbon::today())->count();

        return view('sekolah.petugas.dashboard', compact('totalBuku', 'peminjamanAktif', 'kunjunganHariIni'));
    }
}