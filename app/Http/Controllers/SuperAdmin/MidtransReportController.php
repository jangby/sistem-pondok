<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\Pondok;
use Illuminate\Support\Facades\DB;

class MidtransReportController extends Controller
{
    /**
     * Tampilkan halaman Laporan Rekonsiliasi Midtrans
     */
    public function index(Request $request)
    {
        // Query dasar: Ambil semua transaksi Midtrans yang sudah LUNAS (verified)
        $query = PembayaranTransaksi::where('payment_gateway', 'midtrans')
                    ->where('status_verifikasi', 'verified');
        
        // --- Terapkan Filter ---
        $query->when($request->filled('pondok_id'), function ($q) use ($request) {
            return $q->where('pondok_id', $request->pondok_id);
        });
        $query->when($request->filled('tanggal_mulai'), function ($q) use ($request) {
            $q->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        });
        $query->when($request->filled('tanggal_selesai'), function ($q) use ($request) {
            $q->whereDate('tanggal_bayar', '<=', $request->tanggal_selesai);
        });

        // --- Ambil Data ---
        
        // 1. Ambil data per Pondok (untuk tabel ringkasan)
        $laporanPerPondok = (clone $query) // Kloning query agar filter tetap ada
            ->join('pondoks', 'pembayaran_transaksis.pondok_id', '=', 'pondoks.id')
            ->select(
                'pondok_id',
                'pondoks.name as nama_pondok',
                DB::raw('COUNT(pembayaran_transaksis.id) as jumlah_transaksi'),
                DB::raw('SUM(pembayaran_transaksis.total_bayar) as total_bruto'), // Ini (55.000)
                DB::raw('SUM(pembayaran_transaksis.biaya_admin) as total_biaya_admin'), // Ini (5.000)
                DB::raw('SUM(pembayaran_transaksis.total_bayar - pembayaran_transaksis.biaya_admin) as total_netto') // Ini (50.000)
            )
            ->groupBy('pondok_id', 'pondoks.name')
            ->get();
            
        // 2. Ambil data transaksi rinci (untuk tabel di bawah)
        $transaksis = (clone $query)
            ->with('pondok', 'orangTua', 'tagihan.santri')
            ->latest('tanggal_bayar')
            ->paginate(20)
            ->withQueryString();
            
        // 3. Ambil data untuk Form Filter
        $pondoks = Pondok::orderBy('name')->get();

        return view('superadmin.midtrans-report.index', compact(
            'laporanPerPondok',
            'transaksis',
            'pondoks'
        ));
    }
}