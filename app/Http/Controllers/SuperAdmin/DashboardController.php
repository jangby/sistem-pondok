<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\Payout;
use App\Models\Pondok;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === 1. KPI TUGAS UTAMA (PAYOUT) ===
        $pendingPayouts = Payout::where('status', 'pending');
        $kpi_pending_payout_count = $pendingPayouts->count();
        $kpi_pending_payout_amount = $pendingPayouts->sum('total_amount');

        // === 2. KPI PEMASUKAN ANDA (FEE ADMIN) ===
        $queryVerifiedMidtrans = PembayaranTransaksi::where('payment_gateway', 'midtrans')
                                    ->where('status_verifikasi', 'verified');
        
        // Total pendapatan fee Anda (sepanjang masa)
        $kpi_total_revenue = (clone $queryVerifiedMidtrans)->sum('biaya_admin');
        
        // Pemasukan fee bulan ini
        $kpi_revenue_this_month = (clone $queryVerifiedMidtrans)
                                    ->whereMonth('tanggal_bayar', now()->month)
                                    ->whereYear('tanggal_bayar', now()->year)
                                    ->sum('biaya_admin');

        // === 3. KPI STATISTIK PLATFORM ===
        $kpi_total_pondok = Pondok::count();
        $kpi_total_santri = Santri::count(); // Semua santri di platform

        // === 4. DATA GRAFIK (PEMASUKAN FEE 7 HARI TERAKHIR) ===
        $revenueChartData = (clone $queryVerifiedMidtrans)
            ->where('tanggal_bayar', '>=', now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(tanggal_bayar) as tanggal'),
                DB::raw('SUM(biaya_admin) as total')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Format untuk Chart.js (labels: ['tgl 1', 'tgl 2'], data: [10000, 20000])
        $chartLabels = $revenueChartData->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)));
        $chartData = $revenueChartData->pluck('total');

        // === 5. DAFTAR TUGAS (5 PAYOUT TERTUNDA) ===
        $daftarPendingPayouts = $pendingPayouts->with('pondok')
                                    ->latest('requested_at')
                                    ->take(5)
                                    ->get();

        return view('superadmin.dashboard', compact(
            'kpi_pending_payout_count', 'kpi_pending_payout_amount',
            'kpi_total_revenue', 'kpi_revenue_this_month',
            'kpi_total_pondok', 'kpi_total_santri',
            'chartLabels', 'chartData',
            'daftarPendingPayouts'
        ));
    }
}