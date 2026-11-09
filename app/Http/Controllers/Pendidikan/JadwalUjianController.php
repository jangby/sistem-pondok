<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalUjianDiniyah;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\Ustadz;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Santri;
use App\Models\AbsensiUjianDiniyah;
use App\Models\NilaiPesantren;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Pondok;

class JadwalUjianController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        $pondokId = $user->pondokStaff?->pondok_id ?? $user->pondok_id;

        abort_unless($pondokId, 403, 'Akun pendidikan tidak terhubung dengan pondok.');

        return $pondokId;
    }

    private function findPondokJadwal($id): JadwalUjianDiniyah
    {
        return JadwalUjianDiniyah::where('pondok_id', $this->getPondokId())
            ->findOrFail($id);
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        $mustawaId = $request->input('mustawa_id');
        $jenisUjian = $request->input('jenis_ujian', 'uas');
        $semester = $request->input('semester', 'ganjil');

        $query = JadwalUjianDiniyah::where('pondok_id', $pondokId)
            ->with(['mustawa', 'mapel', 'pengawas'])
            ->where('jenis_ujian', $jenisUjian)
            ->where('semester', $semester);

        if ($mustawaId) {
            $query->where('mustawa_id', $mustawaId);
        }

        $jadwals = $query->orderBy('tanggal')->orderBy('jam_mulai')->paginate(20);
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        return view('pendidikan.admin.ujian.index', compact('jadwals', 'mustawas', 'jenisUjian', 'semester'));
    }

    public function create()
    {
        $pondokId = $this->getPondokId();
        $data = [
            'mustawas' => Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get(),
            'mapels' => MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get(),
            'ustadzs' => Ustadz::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('nama_lengkap')->get(),
        ];
        return view('pendidikan.admin.ujian.create', $data);
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();
        $request->validate([
            'jenis_ujian' => 'required|in:uts,uas',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string|max:20',
            'mustawa_id' => ['required', Rule::exists('mustawas', 'id')->where('pondok_id', $pondokId)],
            'mapel_diniyah_id' => ['required', Rule::exists('mapel_diniyahs', 'id')->where('pondok_id', $pondokId)],
            'pengawas_id' => ['required', Rule::exists('ustadzs', 'id')->where('pondok_id', $pondokId)],
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kategori_tes' => 'required|in:tulis,lisan,praktek,hafalan',
            'ruangan' => 'nullable|string|max:255',
        ]);

        $bentrok = JadwalUjianDiniyah::where('pondok_id', $pondokId)
            ->where('pengawas_id', $request->pengawas_id)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', '<', $request->jam_selesai)
            ->where('jam_selesai', '>', $request->jam_mulai)
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages(['pengawas_id' => 'Ustadz ini sudah ada jadwal mengawas di jam tersebut.']);
        }

        JadwalUjianDiniyah::create($request->only([
            'mustawa_id',
            'mapel_diniyah_id',
            'pengawas_id',
            'jenis_ujian',
            'semester',
            'tahun_ajaran',
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'kategori_tes',
            'ruangan',
        ]) + ['pondok_id' => $pondokId]);

        return redirect()->route('pendidikan.admin.ujian.index')->with('success', 'Jadwal Ujian berhasil dibuat.');
    }

    public function edit($id)
    {
        $pondokId = $this->getPondokId();
        $jadwal = $this->findPondokJadwal($id);

        return view('pendidikan.admin.ujian.edit', [
            'jadwal' => $jadwal,
            'mustawas' => Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get(),
            'mapels' => MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get(),
            'ustadzs' => Ustadz::where('pondok_id', $pondokId)->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $jadwal = $this->findPondokJadwal($id);

        $validated = $request->validate([
            'jenis_ujian' => 'required|in:uts,uas',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string|max:20',
            'mustawa_id' => ['required', Rule::exists('mustawas', 'id')->where('pondok_id', $pondokId)],
            'mapel_diniyah_id' => ['required', Rule::exists('mapel_diniyahs', 'id')->where('pondok_id', $pondokId)],
            'pengawas_id' => ['required', Rule::exists('ustadzs', 'id')->where('pondok_id', $pondokId)],
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kategori_tes' => 'required|in:tulis,lisan,praktek,hafalan',
            'ruangan' => 'nullable|string|max:255',
        ]);

        $bentrok = JadwalUjianDiniyah::where('pondok_id', $pondokId)
            ->where('id', '!=', $jadwal->id)
            ->where('pengawas_id', $validated['pengawas_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'pengawas_id' => 'Ustadz ini sudah ada jadwal mengawas di jam tersebut.',
            ]);
        }

        $jadwal->update($validated);

        return redirect()->route('pendidikan.admin.ujian.index')
            ->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->findPondokJadwal($id)->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }

    // --- LOGIKA UTAMA: SHOW & STORE GRADES (FIXED) ---

    public function show($id)
    {
        $pondokId = $this->getPondokId();
        
        $jadwal = JadwalUjianDiniyah::with(['mustawa', 'mapel', 'pengawas'])
            ->where('pondok_id', $pondokId)
            ->findOrFail($id);

        $santris = Santri::where('pondok_id', $pondokId)
                         ->where('mustawa_id', $jadwal->mustawa_id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        $absensi = AbsensiUjianDiniyah::where('jadwal_ujian_diniyah_id', $id)
                                      ->pluck('status', 'santri_id');

        $nilai = NilaiPesantren::where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
            ->whereIn('santri_id', $santris->pluck('id'))
            ->where('jenis_ujian', $jadwal->jenis_ujian)
            ->where('semester', $jadwal->semester)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->get()
            ->keyBy('santri_id');

        // --- 1. LOGIKA TOTAL PERTEMUAN (PRIORITY: SAVED > LOG) ---
        if ($jadwal->total_pertemuan > 0) {
            // Gunakan data tersimpan agar konsisten
            $totalPertemuan = $jadwal->total_pertemuan;
        } else {
            // Hitung estimasi dari log harian jika belum ada yg disimpan
            $jadwalIds = \App\Models\JadwalDiniyah::where('pondok_id', $pondokId)
                ->where('mustawa_id', $jadwal->mustawa_id)
                ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                ->pluck('id');
            
            $logCount = \App\Models\AbsensiDiniyah::whereIn('jadwal_diniyah_id', $jadwalIds)
                ->select('jadwal_diniyah_id', 'created_at')
                ->distinct()
                ->count();
                
            $totalPertemuan = $logCount > 0 ? $logCount : 14; // Default 14
        }

        // --- 2. LOGIKA JUMLAH HADIR (REVERSE CALCULATION) ---
        $dataKehadiran = [];
        foreach($santris as $santri) {
            $record = $nilai->get($santri->id);

            if ($record && $record->nilai_kehadiran !== null) {
                // Hitung balik: (Persen / 100) * Total = Jumlah Hari
                $savedCount = round(($record->nilai_kehadiran / 100) * $totalPertemuan);
                $dataKehadiran[$santri->id] = $savedCount;
            } else {
                // Jika belum ada nilai, biarkan 0 atau ambil dari log (opsional)
                // Agar bersih, kita set 0 agar Admin menginput real-nya
                $dataKehadiran[$santri->id] = 0; 
            }
        }

        return view('pendidikan.admin.ujian.show', compact('jadwal', 'santris', 'absensi', 'nilai', 'dataKehadiran', 'totalPertemuan'));
    }

    public function storeGrades(Request $request, $id)
    {
        $jadwal = $this->findPondokJadwal($id);
        $kategori = strtolower($jadwal->kategori_tes);

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:100',
            'total_meetings' => 'nullable|integer|min:1',
            'attendance_count' => 'nullable|array',
            'attendance_count.*' => 'nullable|integer|min:0',
        ]);

        $pondokId = $this->getPondokId();
        $allowedSantriIds = Santri::where('pondok_id', $pondokId)
            ->where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();
        if (array_diff(array_map('strval', array_keys($request->input('grades', []))), $allowedSantriIds)) {
            throw ValidationException::withMessages(['grades' => 'Data santri tidak sesuai dengan kelas ujian ini.']);
        }

        DB::transaction(function() use ($jadwal, $request, $kategori) {
            
            // 1. UPDATE TOTAL PERTEMUAN (Hanya jika kategori tulis/utama)
            if ($kategori == 'tulis' && $request->has('total_meetings')) {
                $totalMeetings = $request->input('total_meetings');
                $jadwal->update(['total_pertemuan' => $totalMeetings]);
            } else {
                // Ambil default atau yang sudah tersimpan
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

                // 2. MAPPING NILAI SESUAI KATEGORI
                if ($kategori == 'tulis') {
                    $record->nilai_tulis = $val;
                    
                    // Simpan Kehadiran (Hanya diinput saat ujian tulis)
                    // Logic: Count -> Percent
                    $hadirCount = $request->input('attendance_count.'.$santriId, 0);
                    $persenKehadiran = ($totalMeetings > 0) ? ($hadirCount / $totalMeetings) * 100 : 0;
                    $record->nilai_kehadiran = min($persenKehadiran, 100);
                    
                } elseif ($kategori == 'lisan') {
                    $record->nilai_lisan = $val;
                } elseif ($kategori == 'praktek') {
                    $record->nilai_praktek = $val;
                } elseif ($kategori == 'hafalan') { // [BARU] Logic Hafalan
                    $record->nilai_hafalan = $val;
                }

                // 3. HITUNG RATA-RATA AKHIR (DINAMIS)
                // Kita cek komponen mana saja yang "active" di Mapel ini atau sudah terisi
                // Agar pembaginya (denominator) sesuai.
                
                $mapel = $jadwal->mapel; // Pastikan relasi mapel ter-load
                $components = 0; 
                $sum = 0;

                // Cek komponen Tulis
                if($record->nilai_tulis !== null || $mapel->uji_tulis || $kategori == 'tulis') { 
                   $valTulis = $record->nilai_tulis ?? 0;
                   $sum += $valTulis; 
                   $components++; 
                }

                // Cek komponen Lisan
                if($record->nilai_lisan !== null || $mapel->uji_lisan || $kategori == 'lisan') { 
                   $valLisan = $record->nilai_lisan ?? 0;
                   $sum += $valLisan; 
                   $components++; 
                }

                // Cek komponen Praktek
                if($record->nilai_praktek !== null || $mapel->uji_praktek || $kategori == 'praktek') { 
                   $valPraktek = $record->nilai_praktek ?? 0;
                   $sum += $valPraktek; 
                   $components++; 
                }

                // [BARU] Cek komponen Hafalan
                if($record->nilai_hafalan !== null || $mapel->uji_hafalan || $kategori == 'hafalan') { 
                   $valHafalan = $record->nilai_hafalan ?? 0;
                   $sum += $valHafalan; 
                   $components++; 
                }

                // Cek komponen Kehadiran (Selalu dihitung jika > 0 atau jika ini ujian tulis)
                // Asumsi bobot kehadiran setara dengan 1 komponen ujian. 
                // Jika ingin bobot berbeda (misal 10%), rumusnya perlu diubah.
                // Disini saya pakai rata-rata murni sesuai kode awal.
                if($record->nilai_kehadiran !== null && $record->nilai_kehadiran > 0) { 
                   $sum += $record->nilai_kehadiran; 
                   $components++; 
                }
                
                $record->nilai_akhir = $components > 0 ? ($sum / $components) : 0;
                $record->save();
            }
        });

        return back()->with('success', 'Nilai ujian berhasil disimpan.');
    }

    public function storeAttendance(Request $request, $id)
    {
        $jadwal = $this->findPondokJadwal($id);
        $request->validate([
            'attendance' => 'nullable|array',
            'attendance.*' => 'required|in:H,I,S,A',
        ]);
        $data = $request->input('attendance', []);
        $pondokId = $this->getPondokId();

        DB::transaction(function() use ($jadwal, $data, $pondokId) {
            $santriIds = Santri::where('pondok_id', $pondokId)
                ->where('mustawa_id', $jadwal->mustawa_id)
                ->where('status', 'active')
                ->pluck('id');
            foreach($santriIds as $santriId) {
                AbsensiUjianDiniyah::updateOrCreate(
                    ['jadwal_ujian_diniyah_id' => $jadwal->id, 'santri_id' => $santriId],
                    ['status' => $data[$santriId] ?? 'A']
                );
            }
        });

        return back()->with('success', 'Absensi ujian berhasil disimpan.');
    }

    public function exportPdf($id)
    {
        $jadwal = $this->findPondokJadwal($id)->load(['mustawa', 'mapel', 'pengawas']);
        $pondokId = $this->getPondokId();
        
        $data = Santri::where('pondok_id', $pondokId)->where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get()
            ->map(function($santri) use ($jadwal) {
                $santri->nilai_ujian = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                    ->where('jenis_ujian', $jadwal->jenis_ujian)
                    ->where('semester', $jadwal->semester)
                    ->where('tahun_ajaran', $jadwal->tahun_ajaran)
                    ->first();
                return $santri;
            });

        $pdf = Pdf::loadView('pendidikan.admin.ujian.pdf.ledger', compact('jadwal', 'data'));
        return $pdf->download('Ledger_Nilai.pdf');
    }

    public function exportExcel($id)
    {
        $jadwal = $this->findPondokJadwal($id)->load(['mustawa', 'mapel', 'pengawas']);
        $pondokId = $this->getPondokId();
        
        $data = Santri::where('pondok_id', $pondokId)->where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get()
            ->map(function($santri) use ($jadwal) {
                $santri->nilai_ujian = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                    ->where('jenis_ujian', $jadwal->jenis_ujian)
                    ->where('semester', $jadwal->semester)
                    ->where('tahun_ajaran', $jadwal->tahun_ajaran)
                    ->first();
                return $santri;
            });

        $isExcel = true; 
        return response(view('pendidikan.admin.ujian.pdf.ledger', compact('jadwal', 'data', 'isExcel')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Ledger_Nilai.xls"');
    }

    public function exportFormatNilai($id)
    {
        $jadwal = $this->findPondokJadwal($id)->load(['mustawa', 'mapel', 'pengawas']);
        $pondokId = $this->getPondokId();
        
        // Ambil data santri aktif di mustawa tersebut
        $santris = Santri::where('pondok_id', $pondokId)->where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        // Ambil nama pondok dari user yang login (sesuai logic getPondokId)
        $pondok = Pondok::findOrFail($pondokId);

        $judul = "FORMAT NILAI - " . strtoupper($jadwal->mapel->nama_mapel) . " (" . $jadwal->mustawa->nama . ")";
        
        $pdf = Pdf::loadView('pendidikan.admin.ujian.pdf.format-nilai', compact('jadwal', 'santris', 'pondok', 'judul'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Format_Nilai_' . $jadwal->mapel->nama_mapel . '.pdf');
    }

    public function exportDaftarHadir($id)
    {
        $jadwal = $this->findPondokJadwal($id)->load(['mustawa', 'mapel', 'pengawas']);
        $pondokId = $this->getPondokId();
        
        $santris = Santri::where('pondok_id', $pondokId)->where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $pondok = Pondok::findOrFail($pondokId);
        
        $judul = "DAFTAR HADIR - " . strtoupper($jadwal->mapel->nama_mapel) . " (" . $jadwal->mustawa->nama . ")";

        $pdf = Pdf::loadView('pendidikan.admin.ujian.pdf.daftar-hadir', compact('jadwal', 'santris', 'pondok', 'judul'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Daftar_Hadir_Ujian_' . $jadwal->mapel->nama_mapel . '.pdf');
    }
}