<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- IMPORT DB
use App\Models\Santri; // <-- IMPORT
use App\Models\PembayaranTransaksi; // <-- IMPORT

class DashboardController extends Controller
{
    public function index()
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // 1. KPI: Total Tunggakan (dari semua santri)
        // Ini adalah query paling efisien untuk menghitung total sisa tagihan
        $totalTunggakan = DB::table('tagihan_details')
            ->join('tagihans', 'tagihan_details.tagihan_id', '=', 'tagihans.id')
            ->join('santris', 'tagihans.santri_id', '=', 'santris.id')
            ->where('santris.pondok_id', $pondokId)
            ->where('tagihan_details.status_item', 'pending')
            ->sum('tagihan_details.sisa_tagihan_item');

        // 2. KPI: Pemasukan Bulan Ini (yang sudah verified)
        $pemasukanBulanIni = PembayaranTransaksi::where('pondok_id', $pondokId)
            ->where('status_verifikasi', 'verified')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('total_bayar');

        // 3. KPI: Jumlah Santri Aktif
        $totalSantriAktif = Santri::where('pondok_id', $pondokId)
                            ->where('status', 'active')
                            ->count();
                            
        // 4. Aktivitas Terbaru: 5 Pembayaran terakhir
        $recentPayments = PembayaranTransaksi::where('pondok_id', $pondokId)
            ->where('status_verifikasi', 'verified')
            ->with('orangTua', 'tagihan.santri') // Eager load
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        return view('adminpondok.dashboard', compact(
            'totalTunggakan',
            'pemasukanBulanIni',
            'totalSantriAktif',
            'recentPayments'
        ));
    }
}