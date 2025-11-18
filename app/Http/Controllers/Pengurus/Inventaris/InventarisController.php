<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\Kerusakan;
use App\Models\Inventaris\Peminjaman;

class InventarisController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        $pondokId = $this->getPondokId();

        // KPI Statistik
        $totalAset = Barang::where('pondok_id', $pondokId)->count();
        $totalRusak = Kerusakan::where('pondok_id', $pondokId)->where('status', 'dilaporkan')->sum('qty');
        $totalPinjam = Peminjaman::where('pondok_id', $pondokId)->where('status', 'active')->sum('qty');
        
        // Nilai Aset (Estimasi)
        $nilaiAset = Barang::where('pondok_id', $pondokId)
            ->get()
            ->sum(function($item) {
                return $item->price * $item->qty_good;
            });

        return view('pengurus.inventaris.dashboard', compact('totalAset', 'totalRusak', 'totalPinjam', 'nilaiAset'));
    }
}