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
    public function index()
    {
        $ustadz = auth()->user()->ustadz;
        $jadwals = JadwalUjianDiniyah::where('pengawas_id', $ustadz->id)
            ->with(['mapel', 'mustawa'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return view('pendidikan.ustadz.ujian.index', compact('jadwals'));
    }

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

        // --- PERBAIKAN LOGIKA TOTAL PERTEMUAN ---
        
        // 1. Cek apakah Ustadz sudah pernah simpan "Total Pertemuan" manual?
        if ($jadwal->total_pertemuan > 0) {
            // Jika ada, pakai data yang disimpan (konsisten!)
            $totalPertemuan = $jadwal->total_pertemuan;
        } else {
            // Jika belum (0), hitung estimasi dari Log Harian
            $jadwalIds = \App\Models\JadwalDiniyah::where('mustawa_id', $jadwal->mustawa_id)
                ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                ->pluck('id');
            
            $hitungLog = \App\Models\AbsensiDiniyah::whereIn('jadwal_diniyah_id', $jadwalIds)
                ->select('jadwal_diniyah_id', 'created_at')
                ->distinct()
                ->count();
                
            $totalPertemuan = $hitungLog > 0 ? $hitungLog : 14; // Default 14 jika kosong
        }

        // 2. Hitung Balik Jumlah Hadir (Reverse Calculation)
        $dataKehadiran = [];
        foreach($santris as $santri) {
            $record = $nilai->get($santri->id);

            if ($record && $record->nilai_kehadiran !== null) {
                // Rumus: (Nilai% / 100) * TotalPertemuan
                $savedCount = round(($record->nilai_kehadiran / 100) * $totalPertemuan);
                $dataKehadiran[$santri->id] = $savedCount;
            } else {
                $dataKehadiran[$santri->id] = 0; 
            }
        }

        return view('pendidikan.ustadz.ujian.show', compact('jadwal', 'santris', 'absensi', 'nilai', 'dataKehadiran', 'totalPertemuan'));
    }

    public function storeNilai(Request $request, $id)
    {
        $jadwal = JadwalUjianDiniyah::findOrFail($id);
        $kategori = strtolower($jadwal->kategori_tes); 

        $request->validate([
            'grades.*' => 'nullable|numeric|min:0|max:100',
            'total_meetings' => 'nullable|numeric|min:1', // Tambahkan validasi ini
        ]);

        DB::transaction(function() use ($jadwal, $request, $kategori) {
            
            // 1. SIMPAN TOTAL PERTEMUAN KE JADWAL (Supaya tidak berubah-ubah)
            if ($kategori == 'tulis' && $request->has('total_meetings')) {
                $totalMeetings = $request->input('total_meetings');
                $jadwal->update(['total_pertemuan' => $totalMeetings]);
            } else {
                // Fallback jika bukan update tulis atau tidak dikirim
                $totalMeetings = $jadwal->total_pertemuan > 0 ? $jadwal->total_pertemuan : 14;
            }

            foreach($request->grades as $santriId => $val) {
                $val = is_numeric($val) ? $val : 0;

                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $jadwal->mapel_diniyah_id,
                    'jenis_ujian' => $jadwal->jenis_ujian,
                    'semester' => $jadwal->semester,
                    'tahun_ajaran' => $jadwal->tahun_ajaran,
                ]);

                $record->mustawa_id = $jadwal->mustawa_id;

                if ($kategori == 'tulis') {
                    $record->nilai_tulis = $val;
                    
                    // Simpan Kehadiran dengan Pembagi yang Konsisten
                    $hadirCount = $request->input('attendance_count.'.$santriId, 0);
                    $persenKehadiran = ($hadirCount / $totalMeetings) * 100;
                    $record->nilai_kehadiran = min($persenKehadiran, 100);
                    
                } elseif ($kategori == 'lisan') {
                    $record->nilai_lisan = $val;
                } elseif ($kategori == 'praktek') {
                    $record->nilai_praktek = $val;
                } elseif ($kategori == 'hafalan') {
                    $record->nilai_hafalan = $val;
                }

                // Hitung Rata-rata
                $pembagi = 0; 
                $total = 0;

                if($record->nilai_tulis > 0 || $kategori == 'tulis') { $total += $record->nilai_tulis; $pembagi++; }
                if($record->nilai_lisan > 0 || $kategori == 'lisan') { $total += $record->nilai_lisan; $pembagi++; }
                if($record->nilai_praktek > 0 || $kategori == 'praktek') { $total += $record->nilai_praktek; $pembagi++; }
                if($record->nilai_hafalan > 0 || $kategori == 'hafalan') { $total += $record->nilai_hafalan; $pembagi++; }
                if($record->nilai_kehadiran > 0) { $total += $record->nilai_kehadiran; $pembagi++; }
                
                $record->nilai_akhir = $pembagi > 0 ? ($total / $pembagi) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Nilai dan Data Kehadiran berhasil disimpan.');
    }
    
    public function storeAbsensi(Request $request, $id)
    {
        // ... (Biarkan fungsi ini tetap sama) ...
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
}