<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\Santri;
use App\Models\NilaiPesantren;
use App\Models\AbsensiUjianDiniyah;
use App\Models\JadwalUjianDiniyah;

class MonitoringNilaiUjianController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    private function getActiveSemester()
    {
        // Anda bisa mengambil ini dari Setting Pondok jika ada, 
        // Disini saya set default atau ambil dari request
        return request('semester', 'ganjil');
    }

    private function getActiveTahunAjaran()
    {
        // Sesuaikan dengan format tahun ajaran di sistem Anda
        return request('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
    }

    // === LEVEL 1: DASHBOARD MUSTAWA ===
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();

        // Ambil semua mustawa
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        // Hitung progres per mustawa
        $mustawas->map(function ($m) use ($semester, $tahunAjaran, $pondokId) {
            $totalMapel = MapelDiniyah::where('pondok_id', $pondokId)->count();
            $totalSantri = Santri::where('mustawa_id', $m->id)->where('status', 'active')->count();
            
            if ($totalMapel == 0 || $totalSantri == 0) {
                $m->progress = 0;
            } else {
                // Hitung rata-rata progres dari semua mapel di mustawa ini
                // Ini estimasi kasar agar tidak terlalu berat query-nya di halaman depan
                $totalExpected = $totalSantri * $totalMapel; 
                $actualFilled = NilaiPesantren::where('mustawa_id', $m->id)
                    ->where('semester', $semester)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->whereNotNull('nilai_akhir')
                    ->count();
                
                $m->progress = round(($actualFilled / $totalExpected) * 100);
                // Batasi max 100 (jika ada data sampah)
                $m->progress = min($m->progress, 100);
            }
            return $m;
        });

        return view('pendidikan.admin.monitoring.ujian.index', compact('mustawas', 'semester', 'tahunAjaran'));
    }

    // === LEVEL 2: LIST MAPEL ===
    public function showMapel(Request $request, $mustawaId)
    {
        $pondokId = $this->getPondokId();
        $semester = $this->getActiveSemester();
        $tahunAjaran = $this->getActiveTahunAjaran();
        
        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($mustawaId);
        $mapels = MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get();
        $santriCount = Santri::where('mustawa_id', $mustawaId)->where('status', 'active')->count();

        $mapels->map(function ($mapel) use ($mustawaId, $semester, $tahunAjaran, $santriCount) {
            if ($santriCount == 0) {
                $mapel->progress = 0;
                return $mapel;
            }

            // Hitung komponen aktif
            $components = 0;
            $filledSum = 0;

            // Query base
            $query = NilaiPesantren::where('mustawa_id', $mustawaId)
                ->where('mapel_diniyah_id', $mapel->id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran);

            if ($mapel->uji_tulis) {
                $components++;
                $count = (clone $query)->whereNotNull('nilai_tulis')->count();
                $filledSum += ($count / $santriCount) * 100;
            }
            if ($mapel->uji_lisan) {
                $components++;
                $count = (clone $query)->whereNotNull('nilai_lisan')->count();
                $filledSum += ($count / $santriCount) * 100;
            }
            if ($mapel->uji_praktek) {
                $components++;
                $count = (clone $query)->whereNotNull('nilai_praktek')->count();
                $filledSum += ($count / $santriCount) * 100;
            }

            $mapel->progress = $components > 0 ? round($filledSum / $components) : 0;
            return $mapel;
        });

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

        // Hitung progres per jenis
        $progress = [
            'tulis' => 0,
            'lisan' => 0,
            'praktek' => 0
        ];

        if ($santriCount > 0) {
            $baseQuery = NilaiPesantren::where('mustawa_id', $mustawaId)
                ->where('mapel_diniyah_id', $mapelId)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran);

            if ($mapel->uji_tulis) {
                $c = (clone $baseQuery)->whereNotNull('nilai_tulis')->count();
                $progress['tulis'] = round(($c / $santriCount) * 100);
            }
            if ($mapel->uji_lisan) {
                $c = (clone $baseQuery)->whereNotNull('nilai_lisan')->count();
                $progress['lisan'] = round(($c / $santriCount) * 100);
            }
            if ($mapel->uji_praktek) {
                $c = (clone $baseQuery)->whereNotNull('nilai_praktek')->count();
                $progress['praktek'] = round(($c / $santriCount) * 100);
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
        
        // Ambil data santri
        $santris = Santri::where('mustawa_id', $mustawaId)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        // Ambil Nilai yang sudah ada
        $existingNilai = NilaiPesantren::where('mustawa_id', $mustawaId)
            ->where('mapel_diniyah_id', $mapelId)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get()
            ->keyBy('santri_id');

        // Khusus Tulis: Ambil data absensi ujian jika ada jadwalnya
        $absensiUjian = [];
        if ($jenis == 'tulis') {
            // Cari jadwal ujian yang relevan (opsional, jika sistem jadwal ujian digunakan ketat)
            // Disini kita load data absensi santri saja jika ada
            // Logic ini bisa disederhanakan tergantung kebutuhan
        }

        return view('pendidikan.admin.monitoring.ujian.input', compact(
            'mustawa', 'mapel', 'jenis', 'santris', 'existingNilai', 'semester', 'tahunAjaran'
        ));
    }

    // === SAVE / UPDATE NILAI ===
    public function updateNilai(Request $request, $mustawaId, $mapelId, $jenis)
    {
        $request->validate([
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $pondokId = $this->getPondokId();
        $semester = $request->semester;
        $tahunAjaran = $request->tahun_ajaran;

        DB::transaction(function() use ($request, $mustawaId, $mapelId, $jenis, $semester, $tahunAjaran) {
            foreach ($request->nilai as $santriId => $val) {
                
                // Skip jika null agar tidak menimpa data yang mungkin diisi ustadz saat bersamaan
                // Atau, jika admin ingin mengosongkan, logic ini perlu disesuaikan.
                // Disini kita asumsikan admin menginput/mengoreksi.
                
                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $mapelId,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ]);

                // Set field wajib saat create baru
                if (!$record->exists) {
                    $record->mustawa_id = $mustawaId;
                    $record->jenis_ujian = 'uas'; // Default, bisa diubah sesuai filter
                }

                if ($jenis == 'tulis') {
                    $record->nilai_tulis = $val;
                    // Update kehadiran jika ada di request
                    if ($request->has("kehadiran.$santriId")) {
                        $record->nilai_kehadiran = $request->input("kehadiran.$santriId");
                    }
                } elseif ($jenis == 'lisan') {
                    $record->nilai_lisan = $val;
                } elseif ($jenis == 'praktek') {
                    $record->nilai_praktek = $val;
                }

                // Hitung Ulang Nilai Akhir Otomatis
                $cols = 0; $sum = 0;
                // Cek konfigurasi mapel lagi untuk pembagi rata-rata yang valid
                $mapel = MapelDiniyah::find($mapelId);
                
                if ($mapel->uji_tulis) { $sum += $record->nilai_tulis ?? 0; $cols++; }
                if ($mapel->uji_lisan) { $sum += $record->nilai_lisan ?? 0; $cols++; }
                if ($mapel->uji_praktek) { $sum += $record->nilai_praktek ?? 0; $cols++; }
                // Opsional: Kehadiran masuk hitungan atau tidak? Default masuk jika > 0
                if ($record->nilai_kehadiran > 0) { $sum += $record->nilai_kehadiran; $cols++; }

                $record->nilai_akhir = $cols > 0 ? ($sum / $cols) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Data nilai berhasil diperbarui.');
    }
}