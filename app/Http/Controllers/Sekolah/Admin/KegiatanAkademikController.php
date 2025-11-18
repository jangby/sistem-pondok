<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\KegiatanAkademik; // <-- Model baru kita
use App\Models\Sekolah\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KegiatanAkademikController extends Controller
{
    // === HELPER FUNCTIONS ===
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first(); //
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return $sekolah;
    }
    
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }
    
    private function checkOwnership(KegiatanAkademik $kegiatanAkademik)
    {
        if ($kegiatanAkademik->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }
    
    private function getTahunAjaranAktif()
    {
        return TahunAjaran::where('pondok_id', $this->getPondokId())
                            ->where('is_active', true)
                            ->first(); //
    }

    /**
     * Tampilkan daftar Kegiatan Akademik
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        
        $kegiatanQuery = KegiatanAkademik::where('sekolah_id', $sekolah->id); //
        
        // Hanya tampilkan data untuk tahun ajaran aktif
        if ($tahunAjaranAktif) {
             $kegiatanQuery->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } else {
            $kegiatanQuery->where('tahun_ajaran_id', -1); // Trik agar query kosong
        }
           
        $kegiatans = $kegiatanQuery->orderBy('tanggal_mulai', 'desc')->paginate(10);
            
        return view('sekolah.admin.kegiatan-akademik.index', compact('kegiatans', 'tahunAjaranAktif'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        if (!$tahunAjaranAktif) {
            return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                             ->with('error', 'Tidak bisa menambah jadwal. Belum ada Tahun Ajaran yang aktif.');
        }
        
        return view('sekolah.admin.kegiatan-akademik.create', compact('tahunAjaranAktif'));
    }

    /**
     * Simpan Kegiatan Akademik baru
     */
    public function store(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {
            return back()->with('error', 'Sesi Tahun Ajaran Aktif berakhir. Gagal menyimpan.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tipe' => 'required|in:UTS,UAS,Harian,Lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);
        
        // Tambahkan data otomatis
        $validated['sekolah_id'] = $sekolah->id;
        $validated['tahun_ajaran_id'] = $tahunAjaranAktif->id;
        
        KegiatanAkademik::create($validated); //

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan Akademik berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(KegiatanAkademik $kegiatanAkademik)
    {
        $this->checkOwnership($kegiatanAkademik); // Keamanan
        $kegiatanAkademik->load('tahunAjaran'); //
        return view('sekolah.admin.kegiatan-akademik.edit', compact('kegiatanAkademik'));
    }

    /**
     * Update data Kegiatan Akademik
     */
    public function update(Request $request, KegiatanAkademik $kegiatanAkademik)
    {
        $this->checkOwnership($kegiatanAkademik); // Keamanan

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tipe' => 'required|in:UTS,UAS,Harian,Lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
        ]);
        
        $kegiatanAkademik->update($validated); //

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan Akademik berhasil diperbarui.');
    }

    /**
     * Hapus data Kegiatan Akademik
     */
    public function destroy(KegiatanAkademik $kegiatanAkademik)
    {
        $this->checkOwnership($kegiatanAkademik); // Keamanan
        
        // TODO: Nanti tambahkan pengecekan jika kegiatan sudah dipakai di nilai
        
        $kegiatanAkademik->delete(); //

        return redirect()->route('sekolah.admin.kegiatan-akademik.index')
                         ->with('success', 'Jadwal Kegiatan Akademik berhasil dihapus.');
    }
}