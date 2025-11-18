<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\Setoran;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Bendahara
     */
    public function index()
    {
        // 1. Hitung Saldo Kas Saat Ini
        // Rumus: Total Pemasukan - Total Pengeluaran
        $totalPemasukan = Kas::where('tipe', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Kas::where('tipe', 'pengeluaran')->sum('nominal');
        
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        // 2. Ambil Daftar Setoran yang Belum Dikonfirmasi (Pending)
        // Kita asumsikan jika 'dikonfirmasi_pada' masih NULL, berarti statusnya Pending
        $pendingSetorans = Setoran::with('admin') // Load data admin penyetor
            ->whereNull('dikonfirmasi_pada')
            ->latest()
            ->get();

        // Kirim data ke view
        return view('bendahara.dashboard', compact('saldoKas', 'pendingSetorans'));
    }
}