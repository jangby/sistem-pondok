<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    /**
     * Ambil data Tagihan milik anak dari Orang Tua yang sedang login.
     */
    private function getTagihanQuery()
    {
        $orangTua = Auth::user()->orangTua;
        $santriIds = $orangTua->santris->pluck('id');

        return Tagihan::whereIn('santri_id', $santriIds)
                      ->with(['santri', 'jenisPembayaran']);
    }

    /**
     * Tampilkan daftar semua tagihan (Halaman Index).
     * INI ADALAH PERBAIKAN UNTUK SCREENSHOT ANDA.
     */
    public function index()
    {
        $tagihans = $this->getTagihanQuery()
                         ->latest()
                         ->paginate(10);
        
        // GANTI DARI "return 'Anda memiliki...'" MENJADI:
        return view('orangtua.tagihan.index', compact('tagihans'));
    }

    /**
     * Tampilkan detail satu tagihan.
     * INI UNTUK MELENGKAPI HALAMAN.
     */
    public function show($id)
    {
        $tagihan = $this->getTagihanQuery()
                         ->with('tagihanDetails')
                         ->findOrFail($id);
        
        // GANTI DARI "return $tagihan;" MENJADI:
        return view('orangtua.tagihan.show', compact('tagihan'));
    }
}