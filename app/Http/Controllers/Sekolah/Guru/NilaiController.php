<?php

namespace App\Http\Controllers\Sekolah\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sekolah\KegiatanAkademik;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\Nilai;
use App\Models\Santri;

class NilaiController extends Controller
{
    private function getGuruData()
    {
        $guruUser = Auth::user();
        $sekolah = $guruUser->sekolahs()->first();
        if (!$sekolah) abort(403);
        return compact('guruUser', 'sekolah');
    }

    /**
     * Tampilan 1: Daftar Ujian/Kegiatan Akademik
     */
    public function index()
    {
        $data = $this->getGuruData();
        
        $kegiatans = KegiatanAkademik::where('sekolah_id', $data['sekolah']->id)
                        ->orderByDesc('tanggal_mulai')
                        ->get();

        return view('sekolah.guru.nilai.index', compact('kegiatans'));
    }

    /**
     * Tampilan 2: Daftar Kelas yang Diajar Guru
     */
    public function showKelas(KegiatanAkademik $kegiatan)
    {
        $data = $this->getGuruData();
        
        $jadwals = JadwalPelajaran::where('guru_user_id', $data['guruUser']->id)
                        ->where('tahun_ajaran_id', $kegiatan->tahun_ajaran_id)
                        ->with(['kelas', 'mataPelajaran'])
                        ->get()
                        ->groupBy(function($item) {
                            return $item->kelas->nama_kelas . ' - ' . $item->mataPelajaran->nama_mapel;
                        });
        
        $listKelasMapel = [];

        foreach ($jadwals as $key => $items) {
            $firstItem = $items->first();
            $kelas = $firstItem->kelas;
            $mapel = $firstItem->mataPelajaran;

            $totalSantri = $kelas->santris()->where('status', 'active')->count();
            
            // PERBAIKAN QUERY COUNT
            $sudahDinilai = Nilai::where('kegiatan_akademik_id', $kegiatan->id)
                                ->where('mata_pelajaran_id', $mapel->id)
                                ->whereHas('santri', function($q) use ($kelas) {
                                    $q->where('kelas_id', $kelas->id);
                                })
                                ->count();
            
            $persen = ($totalSantri > 0) ? round(($sudahDinilai / $totalSantri) * 100) : 0;

            $listKelasMapel[] = (object) [
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'nama_kelas' => $kelas->nama_kelas,
                'nama_mapel' => $mapel->nama_mapel,
                'total_santri' => $totalSantri,
                'sudah_dinilai' => $sudahDinilai,
                'persen' => $persen
            ];
        }

        return view('sekolah.guru.nilai.list-kelas', compact('kegiatan', 'listKelasMapel'));
    }

    /**
     * Tampilan 3: Form Input Nilai (Mobile Friendly)
     */
    public function formNilai(KegiatanAkademik $kegiatan, $kelasId, $mapelId)
    {
        $data = $this->getGuruData();
        
        $kelas = \App\Models\Kelas::findOrFail($kelasId);
        $mapel = \App\Models\Sekolah\MataPelajaran::findOrFail($mapelId);

        // Validasi: Guru benar-benar mengajar di kelas & mapel ini?
        $isTeaching = JadwalPelajaran::where('guru_user_id', $data['guruUser']->id)
                        ->where('kelas_id', $kelasId)
                        ->where('mata_pelajaran_id', $mapelId)
                        ->exists();
        
        if (!$isTeaching) abort(403, 'Anda tidak mengajar mata pelajaran di kelas ini.');

        // Ambil Santri
        $santris = $kelas->santris()->where('status', 'active')->orderBy('full_name')->get();

        // PERBAIKAN QUERY AMBIL NILAI
        $nilaiExisting = Nilai::where('kegiatan_akademik_id', $kegiatan->id)
                            ->where('mata_pelajaran_id', $mapelId)
                            ->whereHas('santri', function($q) use ($kelasId) {
                                $q->where('kelas_id', $kelasId);
                            })
                            ->pluck('nilai', 'santri_id');

        return view('sekolah.guru.nilai.form', compact('kegiatan', 'kelas', 'mapel', 'santris', 'nilaiExisting'));
    }

    /**
     * Proses Simpan Nilai
     */
    public function store(Request $request, KegiatanAkademik $kegiatan)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'nilai' => 'array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $this->getGuruData();
        $guruUserId = $data['guruUser']->id;

        DB::transaction(function () use ($request, $kegiatan, $guruUserId) {
            foreach ($request->nilai as $santriId => $nilaiValue) {
                if ($nilaiValue === null) continue;

                Nilai::updateOrCreate(
                    [
                        // Kunci pencarian (agar tidak duplikat)
                        'kegiatan_akademik_id' => $kegiatan->id,
                        'santri_id' => $santriId,
                        'mata_pelajaran_id' => $request->mapel_id,
                    ],
                    [
                        // Data yang diupdate/disimpan
                        // PERBAIKAN: Tambahkan tahun_ajaran_id
                        'tahun_ajaran_id' => $kegiatan->tahun_ajaran_id, 
                        'guru_user_id' => $guruUserId,
                        'nilai' => $nilaiValue,
                    ]
                );
            }
        });

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}