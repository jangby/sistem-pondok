<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Tampilkan daftar semua tagihan.
     */
    public function index(Request $request)
    {
        // Query dasar, sudah difilter otomatis oleh Trait BelongsToPondok
        $query = Tagihan::with(['santri', 'jenisPembayaran']);

        // --- TERAPKAN FILTER BARU ---

        // 1. Filter Nama Santri (dari autocomplete)
        $query->when($request->filled('santri_id'), function ($q) use ($request) {
            return $q->where('santri_id', $request->santri_id);
        });

        // 2. Filter Jenis Kelamin (relasi ke santri)
        $query->when($request->filled('jenis_kelamin'), function ($q) use ($request) {
            return $q->whereHas('santri', function ($subQuery) use ($request) {
                $subQuery->where('jenis_kelamin', $request->jenis_kelamin);
            });
        });

        // 3. Filter Tipe Tagihan (relasi ke jenisPembayaran)
        $query->when($request->filled('tipe_tagihan'), function ($q) use ($request) {
            return $q->whereHas('jenisPembayaran', function ($subQuery) use ($request) {
                $subQuery->where('tipe', $request->tipe_tagihan);
            });
        });

        // 4. Filter Status Pembayaran (langsung di tabel tagihan)
        $query->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        });
        
        // Ambil hasil dengan paginasi, dan pertahankan query string filter
        $tagihans = $query->latest()->paginate(20)->withQueryString();

        // Ini diperlukan agar Tom-Select bisa menampilkan nama
        // santri yang sedang difilter saat halaman di-load ulang
        $selectedSantri = null;
        if ($request->filled('santri_id')) {
            $selectedSantri = \App\Models\Santri::find($request->santri_id);
        }

        return view('adminpondok.tagihan.index', compact('tagihans', 'selectedSantri'));
    }

    /**
     * Tampilkan detail satu tagihan.
     */
    public function show(Tagihan $tagihan)
    {
        // Trait otomatis mengamankan
        $tagihan->load(['santri.orangTua', 'tagihanDetails', 'jenisPembayaran']);
        return view('adminpondok.tagihan.show', compact('tagihan'));
    }

    /**
     * Hapus (Batalkan) tagihan.
     */
    public function destroy(Tagihan $tagihan)
    {
        // Trait otomatis mengamankan
        try {
            // Hanya boleh hapus jika statusnya 'pending'
            if ($tagihan->status != 'pending') {
                return redirect()->back()->with('error', 'Gagal! Tagihan yang sudah dibayar (sebagian/lunas) tidak bisa dibatalkan.');
            }

            // Hapus (cascade delete di migrasi akan hapus tagihanDetails)
            $tagihan->delete();

            return redirect()->route('adminpondok.tagihan.index')
                             ->with('success', 'Tagihan berhasil dibatalkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan tagihan: ' . $e->getMessage());
        }
    }
}