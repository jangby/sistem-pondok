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
        
        // Pastikan hanya pengawas yang boleh akses
        $jadwal = JadwalUjianDiniyah::where('pengawas_id', $ustadz->id)
            ->with(['mapel', 'mustawa'])
            ->findOrFail($id);

        // Ambil Santri
        $santris = Santri::where('mustawa_id', $jadwal->mustawa_id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        // Load Data Existing
        $absensi = AbsensiUjianDiniyah::where('jadwal_ujian_diniyah_id', $id)
                                      ->pluck('status', 'santri_id');

        // Load Nilai Existing (Sesuai spesifikasi ujian ini)
        $nilai = NilaiPesantren::where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
            ->where('jenis_ujian', $jadwal->jenis_ujian)
            ->where('semester', $jadwal->semester)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->get()
            ->keyBy('santri_id');

        return view('pendidikan.ustadz.ujian.show', compact('jadwal', 'santris', 'absensi', 'nilai'));
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
            foreach($request->grades as $santriId => $val) {
                // Logic update nilai sama seperti admin
                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $jadwal->mapel_diniyah_id,
                    'jenis_ujian' => $jadwal->jenis_ujian,
                    'semester' => $jadwal->semester,
                    'tahun_ajaran' => $jadwal->tahun_ajaran,
                ]);

                $record->mustawa_id = $jadwal->mustawa_id;

                // Update kolom sesuai kategori tes jadwal ini saja
                if ($jadwal->kategori_tes == 'tulis') $record->nilai_tulis = $val;
                elseif ($jadwal->kategori_tes == 'lisan') $record->nilai_lisan = $val;
                elseif ($jadwal->kategori_tes == 'praktek') $record->nilai_praktek = $val;

                // Hitung rata-rata sederhana
                $cols = 0; $sum = 0;
                if($record->nilai_tulis > 0) { $sum += $record->nilai_tulis; $cols++; }
                if($record->nilai_lisan > 0) { $sum += $record->nilai_lisan; $cols++; }
                if($record->nilai_praktek > 0) { $sum += $record->nilai_praktek; $cols++; }
                
                $record->nilai_akhir = $cols > 0 ? ($sum / $cols) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Nilai ujian berhasil disimpan.');
    }
}