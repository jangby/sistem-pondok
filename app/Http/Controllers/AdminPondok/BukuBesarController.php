<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranTransaksi;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

class BukuBesarController extends Controller
{
    /**
     * Tampilkan halaman Buku Besar.
     */
    public function index(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;

        // Query dasar: Ambil transaksi HANYA yang sudah final (verified/canceled)
        $query = PembayaranTransaksi::where('pondok_id', $pondokId)
                    ->whereIn('status_verifikasi', ['verified', 'canceled'])
                    ->with(['orangTua', 'tagihan.santri', 'adminVerifier']);

        // --- Terapkan Filter ---

        // 1. Filter Nama Santri (dari autocomplete)
        $query->when($request->filled('santri_id'), function ($q) use ($request) {
            return $q->whereHas('tagihan', function ($subQuery) use ($request) {
                $subQuery->where('santri_id', $request->santri_id);
            });
        });

        // 2. Filter Status
        $query->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status_verifikasi', $request->status);
        });

        // 3. Filter Tanggal (berdasarkan tanggal bayar/verifikasi)
        $query->when($request->filled('tanggal_mulai'), function ($q) use ($request) {
            $q->whereDate('tanggal_bayar', '>=', $request->tanggal_mulai);
        });
        $query->when($request->filled('tanggal_selesai'), function ($q) use ($request) {
            $q->whereDate('tanggal_bayar', '<=', $request->tanggal_selesai);
        });
        
        // Eksekusi Query
        $transaksis = $query->latest('tanggal_bayar')->paginate(25)->withQueryString();

        // Ambil data santri yang dipilih (jika ada) untuk Tom-Select
        $selectedSantri = null;
        if ($request->filled('santri_id')) {
            $selectedSantri = Santri::find($request->santri_id);
        }

        return view('adminpondok.buku-besar.index', compact('transaksis', 'selectedSantri'));
    }
}