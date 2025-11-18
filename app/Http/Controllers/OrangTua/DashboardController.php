<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrangTua;
use App\Models\Santri;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Cari Profil Orang Tua berdasarkan User ID yang login
        $orangTua = OrangTua::where('user_id', $user->id)->first();

        // Jika data orang tua belum di-link ke akun user ini
        if (!$orangTua) {
            return view('orangtua.dashboard', [
                'santris' => collect([]),
                'sisaTagihan' => 0,
                'totalTagihan' => 0,
                'totalBayar' => 0
            ]);
        }

        // 2. Ambil Data Santri milik Orang Tua tersebut
        // Kita load relasi yang dibutuhkan di view (dompet/uangJajan, tagihan, kelas)
        $santris = Santri::where('orang_tua_id', $orangTua->id)
            ->with([
                'kelas', 
                'dompet', // Kita hanya butuh ini
                'tagihans' => function($q) {
                    $q->whereIn('status', ['pending', 'partial', 'overdue']);
                }
            ])
            ->get();

        // 3. Hitung Ringkasan Keuangan (Sisa Tagihan)
        $sisaTagihan = 0;

        // Kita perlu menghitung sisa tagihan real (termasuk yang dicicil)
        // Mengambil semua tagihan belum lunas milik semua anak
        $tagihanBelumLunas = \App\Models\Tagihan::whereIn('santri_id', $santris->pluck('id'))
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->with('tagihanDetails')
            ->get();

        foreach ($tagihanBelumLunas as $tagihan) {
            // Jumlahkan sisa dari setiap item tagihan
            $sisaTagihan += $tagihan->tagihanDetails->sum('sisa_tagihan_item');
        }

        return view('orangtua.dashboard', compact('santris', 'sisaTagihan'));
    }
}