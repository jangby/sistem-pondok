<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    // Filter dan Pilih Anggota untuk dicetak
    public function cetakKartu(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        $santris = null;

        if ($request->has('kelas_id')) {
            $santris = Santri::where('pondok_id', $pondokId)
                ->where('kelas_id', $request->kelas_id)
                ->where('status', 'active')
                ->with('kelas')
                ->orderBy('full_name')
                ->get();
        }

        // Ambil list kelas untuk dropdown filter
        $kelasList = \App\Models\Kelas::where('pondok_id', $pondokId)->get();

        // Jika mode cetak (print=true), tampilkan view khusus cetak
        if ($request->has('print') && $santris) {
            return view('sekolah.superadmin.perpus.anggota.print-card', compact('santris'));
        }

        // Jika tidak, tampilkan halaman filter biasa
        return view('sekolah.superadmin.perpus.anggota.filter-card', compact('santris', 'kelasList'));
    }
}