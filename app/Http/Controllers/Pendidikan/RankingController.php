<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\NilaiPesantren;

class RankingController extends Controller
{
    // Halaman Pilih Kelas
    public function index()
    {
        // Ambil pondok ID dari user login (sesuaikan dengan helper Anda)
        $pondokId = auth()->user()->pondokStaff->pondok_id ?? auth()->user()->pondok_id;
        
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->get();

        return view('pendidikan.admin.ranking.index', compact('mustawas'));
    }

    // Halaman Tampil Ranking
    public function show(Request $request)
    {
        $request->validate(['mustawa_id' => 'required']);
        
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
            ->where('status', 'active')
            ->get();

        $rankingData = [];

        foreach ($santris as $santri) {
            // 1. Hitung Akademik (Rata-rata Nilai Akhir Mapel)
            $akademik = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->avg('nilai_akhir') ?? 0;

            // 2. Hitung Disiplin (Ambil Nilai Kehadiran Tertinggi/Rata-rata)
            // Menggunakan logika yang sama dengan RaporController
            $disiplin = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id)
                ->where('nilai_kehadiran', '>', 0)
                ->max('nilai_kehadiran') ?? 0;

            // 3. Hitung Keterampilan (Rata-rata Nilai Praktek & Hafalan)
            // Kita ambil rata-rata dari kolom nilai_praktek dan nilai_hafalan
            $keterampilanQuery = NilaiPesantren::where('santri_id', $santri->id)
                ->where('mustawa_id', $request->mustawa_id);
            
            $avgPraktek = (clone $keterampilanQuery)->whereNotNull('nilai_praktek')->avg('nilai_praktek') ?? 0;
            $avgHafalan = (clone $keterampilanQuery)->whereNotNull('nilai_hafalan')->avg('nilai_hafalan') ?? 0;
            
            // Jika ada keduanya, dirata-rata. Jika salah satu, ambil salah satu.
            if ($avgPraktek > 0 && $avgHafalan > 0) {
                $skill = ($avgPraktek + $avgHafalan) / 2;
            } else {
                $skill = $avgPraktek > 0 ? $avgPraktek : $avgHafalan;
            }

            // 4. Hitung Sikap (Logika Rapor: 60% Kehadiran + 40% Akademik)
            // Atau Anda bisa menggantinya jika ada tabel input nilai sikap manual
            $sikap = ($disiplin * 0.6) + ($akademik * 0.4);

            // === RUMUS BOBOT (TOTAL SCORE) ===
            // Akademik 40%, Disiplin 30%, Sikap 20%, Skill 10%
            $finalScore = ($akademik * 0.40) + 
                          ($disiplin * 0.30) + 
                          ($sikap    * 0.20) + 
                          ($skill    * 0.10);

            $rankingData[] = [
                'nama' => $santri->full_name,
                'nis'  => $santri->nis,
                'akademik' => round($akademik, 1),
                'disiplin' => round($disiplin, 1),
                'sikap'    => round($sikap, 1),
                'skill'    => round($skill, 1),
                'total'    => round($finalScore, 2)
            ];
        }

        // Urutkan dari nilai tertinggi ke terendah
        $rankingData = collect($rankingData)->sortByDesc('total')->values();

        $mustawa = Mustawa::find($request->mustawa_id);

        return view('pendidikan.admin.ranking.show', compact('rankingData', 'mustawa'));
    }
}