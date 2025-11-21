<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalUjianDiniyah;
use App\Models\Santri;
use App\Models\AbsensiUjianDiniyah;
use App\Models\NilaiPesantren;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    // 1. Daftar Jadwal Mengawas
    public function index()
    {
        $ustadz = auth()->user()->ustadz;

        // Ambil jadwal di mana ustadz ini menjadi PENGAWAS
        $jadwals = JadwalUjianDiniyah::where('pengawas_id', $ustadz->id)
            ->with(['mapel', 'mustawa'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('pendidikan.ustadz.ujian.index', compact('jadwals'));
    }

    // 2. Halaman Input (Absen & Nilai)
    public function show($id)
    {
        $ustadz = auth()->user()->ustadz;
        
        $jadwal = JadwalUjianDiniyah::where('pengawas_id', $ustadz->id)
            ->with(['mapel', 'mustawa'])
            ->findOrFail($id);

        $santris = Santri::where('mustawa_id', $jadwal->mustawa_id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        $absensi = AbsensiUjianDiniyah::where('jadwal_ujian_diniyah_id', $id)
                                      ->pluck('status', 'santri_id');

        $nilai = NilaiPesantren::where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
            ->where('jenis_ujian', $jadwal->jenis_ujian)
            ->where('semester', $jadwal->semester)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->get()
            ->keyBy('santri_id');

        // --- LOGIKA BARU: HITUNG % KEHADIRAN HARIAN ---
        // Ambil semua jadwal harian untuk mapel & kelas ini
        $jadwalIds = \App\Models\JadwalDiniyah::where('mustawa_id', $jadwal->mustawa_id)
            ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
            ->pluck('id');

        // Hitung statistik kehadiran per santri
        $statistikKehadiran = [];
        foreach($santris as $santri) {
            // Hitung total pertemuan yang tercatat untuk santri ini
            $totalPertemuan = \App\Models\AbsensiDiniyah::whereIn('jadwal_diniyah_id', $jadwalIds)
                ->where('santri_id', $santri->id)
                ->count();
            
            // Hitung jumlah hadir
            $totalHadir = \App\Models\AbsensiDiniyah::whereIn('jadwal_diniyah_id', $jadwalIds)
                ->where('santri_id', $santri->id)
                ->where('status', 'H')
                ->count();

            // Kalkulasi Persen (0-100)
            $persen = $totalPertemuan > 0 ? ($totalHadir / $totalPertemuan) * 100 : 100; // Default 100 jika belum ada data
            $statistikKehadiran[$santri->id] = round($persen, 0);
        }
        // ----------------------------------------------

        return view('pendidikan.ustadz.ujian.show', compact('jadwal', 'santris', 'absensi', 'nilai', 'statistikKehadiran'));
    }

    // 3. Simpan Absensi
    public function storeAbsensi(Request $request, $id)
    {
        $jadwal = JadwalUjianDiniyah::findOrFail($id);
        
        DB::transaction(function() use ($jadwal, $request) {
            foreach($request->attendance as $santriId => $status) {
                AbsensiUjianDiniyah::updateOrCreate(
                    ['jadwal_ujian_diniyah_id' => $jadwal->id, 'santri_id' => $santriId],
                    ['status' => $status]
                );
            }
        });

        return back()->with('success', 'Absensi ujian disimpan.');
    }

    // 4. Simpan Nilai
    public function storeNilai(Request $request, $id)
    {
        $jadwal = JadwalUjianDiniyah::findOrFail($id);
        
        DB::transaction(function() use ($jadwal, $request) {
            // Loop berdasarkan grades (nilai ujian utama)
            foreach($request->grades as $santriId => $val) {
                
                // Ambil input nilai kehadiran juga
                $valKehadiran = $request->attendance_score[$santriId] ?? 0;

                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $jadwal->mapel_diniyah_id,
                    'jenis_ujian' => $jadwal->jenis_ujian,
                    'semester' => $jadwal->semester,
                    'tahun_ajaran' => $jadwal->tahun_ajaran,
                ]);

                $record->mustawa_id = $jadwal->mustawa_id;

                // Update Nilai Ujian Utama
                if ($jadwal->kategori_tes == 'tulis') $record->nilai_tulis = $val;
                elseif ($jadwal->kategori_tes == 'lisan') $record->nilai_lisan = $val;
                elseif ($jadwal->kategori_tes == 'praktek') $record->nilai_praktek = $val;

                // Update Nilai Kehadiran (Tersimpan permanen setelah diedit/simpan)
                $record->nilai_kehadiran = $valKehadiran;

                // Hitung Rata-rata Akhir (Termasuk Kehadiran)
                // Rumus: (Nilai Ujian + Nilai Kehadiran) / Jumlah Komponen
                // Atau Anda bisa pakai bobot. Di sini kita pakai rata-rata simpel.
                
                $cols = 0; $sum = 0;
                if($record->nilai_tulis > 0) { $sum += $record->nilai_tulis; $cols++; }
                if($record->nilai_lisan > 0) { $sum += $record->nilai_lisan; $cols++; }
                if($record->nilai_praktek > 0) { $sum += $record->nilai_praktek; $cols++; }
                
                // Tambahkan komponen kehadiran ke rata-rata
                if($record->nilai_kehadiran > 0) { $sum += $record->nilai_kehadiran; $cols++; }
                
                $record->nilai_akhir = $cols > 0 ? ($sum / $cols) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Nilai ujian & kehadiran berhasil disimpan.');
    }
}