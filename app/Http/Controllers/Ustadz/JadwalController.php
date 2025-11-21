<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalDiniyah;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ustadz = $user->ustadz;

        if (!$ustadz) {
            return redirect()->route('ustadz.dashboard')->with('error', 'Profil Ustadz tidak ditemukan.');
        }

        // Ambil semua jadwal ustadz ini, urutkan berdasarkan jam
        $semuaJadwal = JadwalDiniyah::where('ustadz_id', $ustadz->id)
            ->with(['mustawa', 'mapel'])
            ->orderBy('jam_mulai')
            ->get();

        // Urutan Hari yang diinginkan
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];

        // Kelompokkan jadwal berdasarkan hari & urutkan harinya
        $jadwalPerHari = $semuaJadwal->groupBy('hari')
            ->sortBy(function ($item, $key) use ($urutanHari) {
                return array_search($key, $urutanHari);
            });

        return view('pendidikan.ustadz.jadwal.index', compact('jadwalPerHari', 'urutanHari'));
    }
}