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

class JadwalUjianController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Filter Default
        $mustawaId = $request->input('mustawa_id');
        $jenisUjian = $request->input('jenis_ujian', 'uas'); // Default UAS
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
        $request->validate([
            'jenis_ujian' => 'required',
            'semester' => 'required',
            'tahun_ajaran' => 'required',
            'mustawa_id' => 'required|exists:mustawas,id',
            'mapel_diniyah_id' => 'required|exists:mapel_diniyahs,id',
            'pengawas_id' => 'required|exists:ustadzs,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kategori_tes' => 'required',
        ]);

        // Cek Bentrok Jadwal Pengawas
        $bentrok = JadwalUjianDiniyah::where('pondok_id', $this->getPondokId())
            ->where('pengawas_id', $request->pengawas_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists();

        if ($bentrok) {
            throw ValidationException::withMessages(['pengawas_id' => 'Ustadz ini sudah ada jadwal mengawas di jam tersebut.']);
        }

        JadwalUjianDiniyah::create(array_merge($request->all(), ['pondok_id' => $this->getPondokId()]));

        return redirect()->route('pendidikan.admin.ujian.index')->with('success', 'Jadwal Ujian berhasil dibuat.');
    }

    public function destroy($id)
    {
        JadwalUjianDiniyah::where('pondok_id', $this->getPondokId())->findOrFail($id)->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }

    // 1. Halaman Kelola (Dashboard Ujian)
    public function show($id)
    {
        $pondokId = $this->getPondokId();
        $jadwal = JadwalUjianDiniyah::with(['mustawa', 'mapel', 'pengawas'])->where('pondok_id', $pondokId)->findOrFail($id);

        // Ambil Santri di kelas ini
        $santris = Santri::where('mustawa_id', $jadwal->mustawa_id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        // Load Data Absensi (jika ada)
        $absensiData = AbsensiUjianDiniyah::where('jadwal_ujian_diniyah_id', $id)
                                          ->pluck('status', 'santri_id')
                                          ->toArray();

        // Load Data Nilai (jika ada)
        // Kita cari nilai berdasarkan Mapel, Jenis Ujian, Semester, & Tahun Ajaran jadwal ini
        $nilaiData = NilaiPesantren::where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
            ->where('jenis_ujian', $jadwal->jenis_ujian)
            ->where('semester', $jadwal->semester)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->get()
            ->keyBy('santri_id');

        return view('pendidikan.admin.ujian.show', compact('jadwal', 'santris', 'absensiData', 'nilaiData'));
    }

    // 2. Simpan Absensi
    public function storeAttendance(Request $request, $id)
    {
        $jadwal = JadwalUjianDiniyah::findOrFail($id);
        $data = $request->attendance ?? [];

        DB::transaction(function() use ($jadwal, $data) {
            // Ambil semua santri di kelas
            $santriIds = Santri::where('mustawa_id', $jadwal->mustawa_id)->where('status', 'active')->pluck('id');

            foreach($santriIds as $santriId) {
                $status = $data[$santriId] ?? 'A'; // Default Alpha jika tidak dicentang/pilih
                
                AbsensiUjianDiniyah::updateOrCreate(
                    ['jadwal_ujian_diniyah_id' => $jadwal->id, 'santri_id' => $santriId],
                    ['status' => $status]
                );
            }
        });

        return back()->with('success', 'Absensi ujian berhasil disimpan.');
    }

    // 3. Simpan Nilai
    public function storeGrades(Request $request, $id)
    {
        $jadwal = JadwalUjianDiniyah::findOrFail($id);
        $grades = $request->grades ?? []; // Array [santri_id => nilai]

        DB::transaction(function() use ($jadwal, $grades) {
            foreach($grades as $santriId => $nilai) {
                // Validasi nilai 0-100
                $nilai = max(0, min(100, (float)$nilai));

                // Cari atau Buat Record Nilai
                $record = NilaiPesantren::firstOrNew([
                    'santri_id' => $santriId,
                    'mapel_diniyah_id' => $jadwal->mapel_diniyah_id,
                    'jenis_ujian' => $jadwal->jenis_ujian,
                    'semester' => $jadwal->semester,
                    'tahun_ajaran' => $jadwal->tahun_ajaran,
                ]);

                // Update kolom yang sesuai dengan kategori tes jadwal ini
                if ($jadwal->kategori_tes == 'tulis') {
                    $record->nilai_tulis = $nilai;
                } elseif ($jadwal->kategori_tes == 'lisan') {
                    $record->nilai_lisan = $nilai;
                } elseif ($jadwal->kategori_tes == 'praktek') {
                    $record->nilai_praktek = $nilai;
                }

                // Pastikan kolom mustawa terisi (history kelas saat ujian)
                $record->mustawa_id = $jadwal->mustawa_id;
                
                // Hitung Nilai Akhir Sederhana (Rata-rata dari kolom yang tidak 0)
                // Atau gunakan bobot jika ada. Di sini kita pakai rata-rata simpel dulu.
                $pembagi = 0;
                $total = 0;
                if($record->nilai_tulis > 0) { $total += $record->nilai_tulis; $pembagi++; }
                if($record->nilai_lisan > 0) { $total += $record->nilai_lisan; $pembagi++; }
                if($record->nilai_praktek > 0) { $total += $record->nilai_praktek; $pembagi++; }
                
                $record->nilai_akhir = $pembagi > 0 ? ($total / $pembagi) : 0;
                
                $record->save();
            }
        });

        return back()->with('success', 'Nilai ujian berhasil disimpan.');
    }

    // 4. Export PDF Ledger
    public function exportPdf($id)
    {
        $jadwal = JadwalUjianDiniyah::with(['mustawa', 'mapel', 'pengawas'])->findOrFail($id);
        
        // Ambil data lengkap (Santri + Nilai)
        $data = Santri::where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get()
            ->map(function($santri) use ($jadwal) {
                $nilai = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                    ->where('jenis_ujian', $jadwal->jenis_ujian)
                    ->where('semester', $jadwal->semester)
                    ->where('tahun_ajaran', $jadwal->tahun_ajaran)
                    ->first();
                
                $santri->nilai_ujian = $nilai;
                return $santri;
            });

        $pdf = Pdf::loadView('pendidikan.admin.ujian.pdf.ledger', compact('jadwal', 'data'));
        return $pdf->download('Ledger_Nilai_'.$jadwal->mapel->nama_mapel.'.pdf');
    }

    // 5. Export Excel (Versi HTML Table sederhana)
    public function exportExcel($id)
    {
        $jadwal = JadwalUjianDiniyah::with(['mustawa', 'mapel', 'pengawas'])->findOrFail($id);
        
        $data = Santri::where('mustawa_id', $jadwal->mustawa_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get()
            ->map(function($santri) use ($jadwal) {
                $nilai = NilaiPesantren::where('santri_id', $santri->id)
                    ->where('mapel_diniyah_id', $jadwal->mapel_diniyah_id)
                    ->where('jenis_ujian', $jadwal->jenis_ujian)
                    ->where('semester', $jadwal->semester)
                    ->where('tahun_ajaran', $jadwal->tahun_ajaran)
                    ->first();
                
                $santri->nilai_ujian = $nilai;
                return $santri;
            });

        // 1. Buat variabelnya dulu
$isExcel = true; 

// 2. Masukkan 'isExcel' ke dalam compact
return response(view('pendidikan.admin.ujian.pdf.ledger', compact('jadwal', 'data', 'isExcel')))
    ->header('Content-Type', 'application/vnd.ms-excel')
    ->header('Content-Disposition', 'attachment; filename="Ledger_Nilai_'.$jadwal->mapel->nama_mapel.'.xls"');
    }
}