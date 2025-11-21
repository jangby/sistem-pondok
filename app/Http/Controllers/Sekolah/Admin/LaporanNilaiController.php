<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sekolah\KegiatanAkademik;
use App\Models\Sekolah\MataPelajaran;
use App\Models\Sekolah\Nilai;
use App\Models\Kelas;
use App\Models\Santri;
use PDF;

class LaporanNilaiController extends Controller
{
    // === HELPER FUNCTIONS ===

    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return $sekolah;
    }
    
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    private function checkOwnership(KegiatanAkademik $kegiatan)
    {
        if ($kegiatan->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }

    private function calculateCompletion($kelasId, $kegiatanId, $mapelId = null)
    {
        // 1. Hitung total siswa aktif di kelas tersebut
        $totalSantri = Santri::where('kelas_id', $kelasId)->where('status', 'active')->count();

        // 2. Tentukan Total Harapan Input (Expected)
        if ($mapelId) {
            $expectedRecords = $totalSantri;
        } else {
            $totalMapel = MataPelajaran::where('sekolah_id', $this->getSekolah()->id)->count();
            if ($totalMapel == 0) return 100;
            $expectedRecords = $totalSantri * $totalMapel;
        }

        if ($expectedRecords == 0) return 100;
        
        // 3. Hitung Jumlah Record Nilai yang Sudah Ada (Actual)
        $actualRecords = Nilai::where('kegiatan_akademik_id', $kegiatanId)
                    ->where('mata_pelajaran_id', $mapelId)
                    ->whereHas('santri', function ($query) use ($kelasId) {
                        $query->where('kelas_id', $kelasId);
                    })
                    ->count();

        // 4. Hitung Persentase
        $completion = round(($actualRecords / $expectedRecords) * 100, 1);
        
        return $completion;
    }

    // === TAMPILAN (VIEWS) ===

    /**
     * Tampilan 1: Daftar Kelas
     */
    /**
     * Tampilan 1: Daftar Kelas (Updated dengan Search)
     */
    public function showKelas(Request $request, KegiatanAkademik $kegiatan)
    {
        $this->checkOwnership($kegiatan);
        
        $sekolah = $this->getSekolah();
        
        // 1. Query Dasar
        $query = Kelas::where('pondok_id', $this->getPondokId())
                      ->where('tingkat', $sekolah->tingkat);

        // 2. Fitur Pencarian (Jika ada input search)
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kelas', 'like', '%' . $request->search . '%');
        }

        // 3. Ambil Data
        $kelases = $query->orderBy('nama_kelas')->get();

        // 4. Hitung Progress (Mapping)
        $kelasList = $kelases->map(function ($kelas) use ($kegiatan) {
            $completion = $this->calculateCompletion($kelas->id, $kegiatan->id);
            $kelas->completion = $completion;
            return $kelas;
        });
            
        return view('sekolah.admin.laporan-nilai.kelas', compact('kegiatan', 'kelasList'));
    }

    /**
     * Tampilan 2: Daftar Mata Pelajaran (Updated dengan Search)
     */
    public function showMapel(Request $request, KegiatanAkademik $kegiatan, Kelas $kelas)
    {
        $this->checkOwnership($kegiatan);
        
        // 1. Query Dasar Mapel Sekolah Ini
        $query = MataPelajaran::where('sekolah_id', $kegiatan->sekolah_id);

        // 2. Fitur Pencarian (Jika ada input search)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_mapel', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Ambil Data
        $mapels = $query->orderBy('nama_mapel')->get();

        // 4. Hitung Progress Per Mapel
        $mapelList = $mapels->map(function ($mapel) use ($kegiatan, $kelas) {
            $completion = $this->calculateCompletion($kelas->id, $kegiatan->id, $mapel->id);
            $mapel->completion = $completion;
            return $mapel;
        });
            
        return view('sekolah.admin.laporan-nilai.mapel', compact('kegiatan', 'kelas', 'mapelList'));
    }

    /**
     * Tampilan 3: Tabel Nilai (Updated)
     */
    public function showNilaiTable(Request $request, KegiatanAkademik $kegiatan, Kelas $kelas, MataPelajaran $mapel)
    {
        $this->checkOwnership($kegiatan);
        
        // 1. Query Santri
        $query = $kelas->santris()->where('status', 'active');

        // 2. Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $santris = $query->orderBy('full_name')->get();

        // 3. Ambil Nilai
        $existingNilai = Nilai::where('kegiatan_akademik_id', $kegiatan->id)
                            ->where('mata_pelajaran_id', $mapel->id)
                            ->whereHas('santri', function ($q) use ($kelas) {
                                $q->where('kelas_id', $kelas->id);
                            })
                            ->pluck('nilai', 'santri_id');

        return view('sekolah.admin.laporan-nilai.nilai', compact('kegiatan', 'kelas', 'mapel', 'santris', 'existingNilai'));
    }

    /**
     * Cetak PDF Ledger Nilai
     */
    public function cetakLedgerNilai(KegiatanAkademik $kegiatan, Kelas $kelas, MataPelajaran $mapel)
    {
        $this->checkOwnership($kegiatan);
        
        $santris = $kelas->santris()->where('status', 'active')->orderBy('full_name')->get();

        $existingNilai = Nilai::where('kegiatan_akademik_id', $kegiatan->id)
                            ->where('mata_pelajaran_id', $mapel->id)
                            ->whereHas('santri', function ($q) use ($kelas) {
                                $q->where('kelas_id', $kelas->id);
                            })
                            ->pluck('nilai', 'santri_id');

        $judul = "LEDGER NILAI - {$mapel->nama_mapel} ({$kelas->nama_kelas})";
        
        $pdf = PDF::loadView('sekolah.admin.laporan-nilai.pdf.ledger-nilai', compact(
            'judul', 'kegiatan', 'kelas', 'mapel', 'santris', 'existingNilai'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Ledger_Nilai_' . $mapel->kode_mapel . '_' . $kelas->nama_kelas . '.pdf');
    }

    /**
     * Cetak Format Nilai Kosong (Untuk pegangan Guru)
     */
    public function cetakFormatNilai(KegiatanAkademik $kegiatan, Kelas $kelas, MataPelajaran $mapel)
    {
        $this->checkOwnership($kegiatan);
        
        // Ambil santri aktif, urutkan nama
        $santris = $kelas->santris()->where('status', 'active')->orderBy('full_name')->get();

        $judul = "FORMAT NILAI - {$mapel->nama_mapel} ({$kelas->nama_kelas})";
        
        $pdf = PDF::loadView('sekolah.admin.laporan-nilai.pdf.format-nilai', compact(
            'judul', 'kegiatan', 'kelas', 'mapel', 'santris'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Format_Nilai_' . $mapel->kode_mapel . '.pdf');
    }

    /**
     * Cetak Daftar Hadir Ujian
     */
    public function cetakDaftarHadir(KegiatanAkademik $kegiatan, Kelas $kelas, MataPelajaran $mapel)
    {
        $this->checkOwnership($kegiatan);
        
        $santris = $kelas->santris()->where('status', 'active')->orderBy('full_name')->get();

        $judul = "DAFTAR HADIR - {$mapel->nama_mapel} ({$kelas->nama_kelas})";
        
        $pdf = PDF::loadView('sekolah.admin.laporan-nilai.pdf.daftar-hadir', compact(
            'judul', 'kegiatan', 'kelas', 'mapel', 'santris'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Daftar_Hadir_' . $mapel->kode_mapel . '.pdf');
    }
}