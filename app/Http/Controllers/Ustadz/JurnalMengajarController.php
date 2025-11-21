<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalDiniyah;
use App\Models\JurnalMengajar;
use Carbon\Carbon;

class JurnalMengajarController extends Controller
{
    public function create($jadwal_id)
    {
        $jadwal = JadwalDiniyah::with(['mapel', 'mustawa'])->findOrFail($jadwal_id);
        $ustadzId = auth()->user()->ustadz->id;
        
        // 1. Cek Jurnal Hari Ini (Edit Mode jika ada)
        $jurnalHariIni = JurnalMengajar::where('jadwal_diniyah_id', $jadwal_id)
                                       ->where('tanggal', Carbon::today())
                                       ->first();

        // 2. Ambil 5 Riwayat Terakhir (Untuk referensi 'Kemarin sampai mana?')
        $riwayat = JurnalMengajar::where('jadwal_diniyah_id', $jadwal_id)
                                 ->where('tanggal', '<', Carbon::today())
                                 ->latest('tanggal')
                                 ->take(5)
                                 ->get();

        return view('pendidikan.ustadz.jurnal-kelas.create', compact('jadwal', 'jurnalHariIni', 'riwayat'));
    }

    public function store(Request $request, $jadwal_id)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $ustadzId = auth()->user()->ustadz->id;

        JurnalMengajar::updateOrCreate(
            [
                'jadwal_diniyah_id' => $jadwal_id,
                'ustadz_id' => $ustadzId,
                'tanggal' => Carbon::today(),
            ],
            [
                'materi' => $request->materi,
                'catatan' => $request->catatan,
            ]
        );

        return redirect()->route('ustadz.absensi.menu', $jadwal_id)
                         ->with('success', 'Jurnal materi berhasil disimpan.');
    }
}