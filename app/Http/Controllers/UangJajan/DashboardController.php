<?php

namespace App\Http\Controllers\UangJajan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dompet;
use App\Models\Warung;
use App\Models\TransaksiDompet;
use App\Models\WarungPayout;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin Uang Jajan
     */
    public function index()
    {
        // 1. Statistik Santri
        // Menghitung total uang yang mengendap di dompet santri
        $totalSaldoSantri = Dompet::sum('saldo');
        
        // Menghitung jumlah akun dompet yang aktif
        $totalSantriAktif = Dompet::where('status', 'active')->count();

        // 2. Statistik Transaksi
        // Menghitung jumlah transaksi (Jajan) yang terjadi hari ini
        $transaksiHariIni = TransaksiDompet::where('tipe', 'jajan')
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 3. Statistik Warung
        // Kita perlu mengambil semua warung untuk menghitung saldo total mereka
        // Menggunakan 'with' untuk eager loading relasi user agar query lebih efisien di view
        $warungs = Warung::with('user')->get();
        
        // Menghitung total saldo warung (menggunakan Accessor 'saldo' di model Warung)
        $totalSaldoWarung = $warungs->sum(function ($warung) {
            return $warung->saldo;
        });
        
        $totalWarung = $warungs->count();

        // 4. Statistik Payout (Penarikan)
        // Menghitung jumlah permintaan penarikan yang statusnya 'pending'
        $pendingPayoutCount = WarungPayout::where('status', 'pending')->count();

        // 5. Aktivitas Terbaru (Global)
        // Mengambil 5 transaksi terakhir dari seluruh sistem (Jajan & Topup)
        $recentTransactions = TransaksiDompet::with(['dompet.santri', 'warung'])
            ->latest()
            ->take(5)
            ->get();

        return view('uuj-admin.dashboard', compact(
            'totalSaldoSantri',
            'totalSantriAktif',
            'transaksiHariIni',
            'totalSaldoWarung',
            'totalWarung',
            'pendingPayoutCount',
            'warungs',
            'recentTransactions'
        ));
    }
}