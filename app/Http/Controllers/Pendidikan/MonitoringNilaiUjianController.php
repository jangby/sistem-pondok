<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\Santri;
use App\Models\NilaiPesantren;
use App\Models\JadwalDiniyah;

class MonitoringNilaiUjianController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    private function getActiveSemester()
    {
        return request('semester', 'ganjil');
    }

    private function getActiveTahunAjaran()
    {
        return request('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
    }

    // === LEVEL 1: DASHBOARD MUSTAWA ===
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();

        // 1. Ambil Semua Mustawa (Kelas) Aktif
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->get();

        foreach ($mustawas as $m) {
            
            $totalSantri = Santri::where('mustawa_id', $m->id)
                ->where('status', 'active')
                ->count();

            if ($totalSantri == 0) {
                $m->progress = 0;
                continue;
            }

            // --- [PERBAIKAN] LOGIKA PENGAMBILAN MAPEL ---
            // Mapel diambil jika:
            // 1. Ada di Jadwal Harian (JadwalDiniyah)
            // 2. ATAU Ada di Jadwal Ujian (JadwalUjianDiniyah) -> INI YANG DITAMBAHKAN
            // 3. ATAU Sudah ada nilainya (NilaiPesantren)
            
            $relevantMapels = MapelDiniyah::where('pondok_id', $pondokId)
                ->where(function($q) use ($m, $semester, $tahunAjaran) {
                    // Cek Jadwal Harian
                    $q->whereHas('jadwals', function($subQ) use ($m) {
                        $subQ->where('mustawa_id', $m->id);
                    })
                    // [BARU] Cek Jadwal Ujian
                    ->orWhereIn('id', function($subQ) use ($m, $semester, $tahunAjaran) {
                        $subQ->select('mapel_diniyah_id')
                             ->from('jadwal_ujian_diniyahs')
                             ->where('mustawa_id', $m->id)
                             ->where('semester', $semester)
                             ->where('tahun_ajaran', $tahunAjaran);
                    })
                    // Cek Nilai Existing
                    ->orWhereIn('id', NilaiPesantren::where('mustawa_id', $m->id)
                        ->select('mapel_diniyah_id'));
                })
                ->get();

            if ($relevantMapels->isEmpty()) {
                $m->progress = 0;
                continue;
            }

            // Ambil semua nilai (Optimasi)
            $allNilai = NilaiPesantren::where('mustawa_id', $m->id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->get()
                ->groupBy('mapel_diniyah_id');

            $totalProgressSemuaMapel = 0;

            foreach ($relevantMapels as $mapel) {
                $nilaiMapelIni = $allNilai->get($mapel->id);
                
                $activeComponents = 0;
                $currentMapelProgress = 0;
                
                // Hitung Tulis
                if ($mapel->uji_tulis) {
                    $activeComponents++;
                    $filled = $nilaiMapelIni ? $nilaiMapelIni->whereNotNull('nilai_tulis')->count() : 0;
                    $currentMapelProgress += ($filled / $totalSantri) * 100;
                }
                
                // Hitung Lisan
                if ($mapel->uji_lisan) {
                    $activeComponents++;
                    $filled = $nilaiMapelIni ? $nilaiMapelIni->whereNotNull('nilai_lisan')->count() : 0;
                    $currentMapelProgress += ($filled / $totalSantri) * 100;
                }
                
                // Hitung Praktek
                if ($mapel->uji_praktek) {
                    $activeComponents++;
                    $filled = $nilaiMapelIni ? $nilaiMapelIni->whereNotNull('nilai_praktek')->count() : 0;
                    $currentMapelProgress += ($filled / $totalSantri) * 100;
                }

                $finalMapelProg = $activeComponents > 0 ? ($currentMapelProgress / $activeComponents) : 0;
                $totalProgressSemuaMapel += min($finalMapelProg, 100);
            }

            $m->progress = round($totalProgressSemuaMapel / $relevantMapels->count());
        }

        return view('pendidikan.admin.monitoring.ujian.index', compact('mustawas', 'semester', 'tahunAjaran'));
    }

    // === LEVEL 2: LIST MAPEL ===
    public function showMapel(Request $request, $mustawaId)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();
        
        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($mustawaId);
        
        // --- [PERBAIKAN] Query Mapel Konsisten dengan Index ---
        $mapels = MapelDiniyah::where('pondok_id', $pondokId)
            ->where(function($q) use ($mustawaId, $semester, $tahunAjaran) {
                // 1. Jadwal Harian
                $q->whereHas('jadwals', function($subQ) use ($mustawaId) {
                    $subQ->where('mustawa_id', $mustawaId);
                })
                // 2. [BARU] Jadwal Ujian
                ->orWhereIn('id', function($subQ) use ($mustawaId, $semester, $tahunAjaran) {
                    $subQ->select('mapel_diniyah_id')
                            ->from('jadwal_ujian_diniyahs')
                            ->where('mustawa_id', $mustawaId)
                            ->where('semester', $semester)
                            ->where('tahun_ajaran', $tahunAjaran);
                })
                // 3. Nilai Existing
                ->orWhereIn('id', NilaiPesantren::where('mustawa_id', $mustawaId)
                    ->select('mapel_diniyah_id'));
            })
            ->orderBy('nama_mapel')
            ->get();

        $santriCount = Santri::where('mustawa_id', $mustawaId)->where('status', 'active')->count();

        foreach($mapels as $mapel) {
            if ($santriCount == 0) {
                $mapel->progress = 0;
                continue;
            }

            $activeComponents = 0;
            $totalProgress = 0;

            $baseQuery = NilaiPesantren::where('mustawa_id', $mustawaId)
                ->where('mapel_diniyah_id', $mapel->id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran);

            if ($mapel->uji_tulis) {
                $activeComponents++;
                $filled = (clone $baseQuery)->whereNotNull('nilai_tulis')->count();
                $totalProgress += ($filled / $santriCount) * 100;
            }

            if ($mapel->uji_lisan) {
                $activeComponents++;
                $filled = (clone $baseQuery)->whereNotNull('nilai_lisan')->count();
                $totalProgress += ($filled / $santriCount) * 100;
            }

            if ($mapel->uji_praktek) {
                $activeComponents++;
                $filled = (clone $baseQuery)->whereNotNull('nilai_praktek')->count();
                $totalProgress += ($filled / $santriCount) * 100;
            }

            $mapel->progress = $activeComponents > 0 ? round($totalProgress / $activeComponents) : 0;
            $mapel->progress = min($mapel->progress, 100);
        }

        return view('pendidikan.admin.monitoring.ujian.mapel', compact('mustawa', 'mapels', 'semester', 'tahunAjaran'));
    }

    // === LEVEL 3: PILIH JENIS UJIAN ===
    public function showDetail(Request $request, $mustawaId, $mapelId)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();

        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($mustawaId);
        $mapel = MapelDiniyah::where('pondok_id', $pondokId)->findOrFail($mapelId);
        $santriCount = Santri::where('mustawa_id', $mustawaId)->where('status', 'active')->count();

        $progress = ['tulis' => 0, 'lisan' => 0, 'praktek' => 0];

        if ($santriCount > 0) {
            $baseQuery = NilaiPesantren::where('mustawa_id', $mustawaId)
                ->where('mapel_diniyah_id', $mapelId)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran);

            if ($mapel->uji_tulis) {
                $c = (clone $baseQuery)->whereNotNull('nilai_tulis')->count();
                $progress['tulis'] = min(round(($c / $santriCount) * 100), 100);
            }
            if ($mapel->uji_lisan) {
                $c = (clone $baseQuery)->whereNotNull('nilai_lisan')->count();
                $progress['lisan'] = min(round(($c / $santriCount) * 100), 100);
            }
            if ($mapel->uji_praktek) {
                $c = (clone $baseQuery)->whereNotNull('nilai_praktek')->count();
                $progress['praktek'] = min(round(($c / $santriCount) * 100), 100);
            }
        }

        return view('pendidikan.admin.monitoring.ujian.detail', compact('mustawa', 'mapel', 'progress', 'semester', 'tahunAjaran'));
    }

    // === LEVEL 4: INPUT/EDIT NILAI ===
    public function showInput(Request $request, $mustawaId, $mapelId, $jenis)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();

        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($mustawaId);
        $mapel = MapelDiniyah::where('pondok_id', $pondokId)->findOrFail($mapelId);
        
        $santris = Santri::where('mustawa_id', $mustawaId)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $existingNilai = NilaiPesantren::where('mustawa_id', $mustawaId)
            ->where('mapel_diniyah_id', $mapelId)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get()
            ->keyBy('santri_id');

        return view('pendidikan.admin.monitoring.ujian.input', compact(
            'mustawa', 'mapel', 'jenis', 'santris', 'existingNilai', 'semester', 'tahunAjaran'
        ));
    }

    // === ACTION: UPDATE NILAI ===
    public function updateNilai(Request $request, $mustawaId, $mapelId, $jenis)
    {
        $request->validate([
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'kehadiran.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $semester = $request->semester;
        $tahunAjaran = $request->tahun_ajaran;

        DB::transaction(function() use ($request, $mustawaId, $mapelId, $jenis, $semester, $tahunAjaran) {
            $mapel = MapelDiniyah::find($mapelId);

            foreach ($request->nilai as $santriId => $val) {
                
                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $mapelId,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ]);

                if (!$record->exists) {
                    $record->mustawa_id = $mustawaId;
                    $record->jenis_ujian = 'uas';
                }

                if ($jenis == 'tulis') {
                    $record->nilai_tulis = $val; 
                    if ($request->has("kehadiran.$santriId")) {
                        $record->nilai_kehadiran = $request->input("kehadiran.$santriId");
                    }
                } elseif ($jenis == 'lisan') {
                    $record->nilai_lisan = $val;
                } elseif ($jenis == 'praktek') {
                    $record->nilai_praktek = $val;
                }

                // Kalkulasi Nilai Akhir
                $cols = 0; 
                $sum = 0;
                
                if ($mapel->uji_tulis) { 
                    $sum += $record->nilai_tulis ?? 0; 
                    $cols++; 
                }
                if ($mapel->uji_lisan) { 
                    $sum += $record->nilai_lisan ?? 0; 
                    $cols++; 
                }
                if ($mapel->uji_praktek) { 
                    $sum += $record->nilai_praktek ?? 0; 
                    $cols++; 
                }
                
                $record->nilai_akhir = $cols > 0 ? ($sum / $cols) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Data nilai berhasil disimpan.');
    }
}