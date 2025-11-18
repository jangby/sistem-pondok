<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\JadwalPelajaran; // <-- Model baru kita
use App\Models\Sekolah\Sekolah;
use App\Models\Sekolah\MataPelajaran;
use App\Models\Sekolah\TahunAjaran;
use App\Models\Kelas; //
use App\Models\User; //
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JadwalPelajaranController extends Controller
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
    
    private function checkOwnership(JadwalPelajaran $jadwalPelajaran)
    {
        if ($jadwalPelajaran->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }
    
    private function getTahunAjaranAktif()
    {
        return TahunAjaran::where('pondok_id', $this->getPondokId())
                            ->where('is_active', true)
                            ->first(); //
    }

    // === DROPDOWN DATA HELPER ===
    private function getFormData()
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();

        $kelasList = Kelas::where('pondok_id', $pondokId)
                        ->orderBy('tingkat')->orderBy('nama_kelas')->get(); //
        
        $mapelList = MataPelajaran::where('sekolah_id', $sekolah->id)
                        ->orderBy('nama_mapel')->get(); //
        
        $guruList = User::role('guru') //
                    ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId)) //
                    ->orderBy('name')->get();
                    
        return compact('kelasList', 'mapelList', 'guruList');
    }

    /**
     * Tampilkan daftar Jadwal Pelajaran
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        
        // Ambil jadwal HANYA untuk tahun ajaran aktif
        $jadwalQuery = JadwalPelajaran::where('sekolah_id', $sekolah->id); //
        
        if ($tahunAjaranAktif) {
             $jadwalQuery->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } else {
            // Jika tidak ada tahun ajaran aktif, tampilkan list kosong
            $jadwalQuery->where('tahun_ajaran_id', -1); // Trik agar query kosong
        }
           
        $jadwals = $jadwalQuery->with(['kelas', 'mataPelajaran', 'guru']) // Eager load
            ->orderBy('kelas_id') // Urutkan berdasarkan kelas
            // Urutkan berdasarkan hari (Senin=1, Selasa=2, dst)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai') // Urutkan berdasarkan jam
            ->paginate(20);
            
        return view('sekolah.admin.jadwal-pelajaran.index', compact('jadwals', 'tahunAjaranAktif'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        if (!$tahunAjaranAktif) {
            return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                             ->with('error', 'Tidak bisa menambah jadwal. Belum ada Tahun Ajaran yang aktif.');
        }
        
        $formData = $this->getFormData();
        
        return view('sekolah.admin.jadwal-pelajaran.create', [
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'kelasList' => $formData['kelasList'],
            'mapelList' => $formData['mapelList'],
            'guruList' => $formData['guruList'],
        ]);
    }

    /**
     * Simpan Jadwal Pelajaran baru
     */
    public function store(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {
            return back()->with('error', 'Sesi Tahun Ajaran Aktif berakhir. Gagal menyimpan.');
        }

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_user_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);
        
        // Tambahkan data otomatis
        $validated['sekolah_id'] = $sekolah->id;
        $validated['tahun_ajaran_id'] = $tahunAjaranAktif->id;
        
        // TODO: Tambahkan validasi anti-bentrok jam

        JadwalPelajaran::create($validated); //

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran); // Keamanan
        
        $formData = $this->getFormData();
        $jadwalPelajaran->load('tahunAjaran'); // Load relasi tahun ajaran

        return view('sekolah.admin.jadwal-pelajaran.edit', [
            'jadwalPelajaran' => $jadwalPelajaran,
            'kelasList' => $formData['kelasList'],
            'mapelList' => $formData['mapelList'],
            'guruList' => $formData['guruList'],
        ]);
    }

    /**
     * Update data Jadwal Pelajaran
     */
    public function update(Request $request, JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran); // Keamanan

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_user_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);
        
        // TODO: Tambahkan validasi anti-bentrok jam

        $jadwalPelajaran->update($validated); //

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus data Jadwal Pelajaran
     */
    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran); // Keamanan
        
        // TODO: Nanti tambahkan pengecekan jika jadwal sudah dipakai di absensi
        
        $jadwalPelajaran->delete(); //

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil dihapus.');
    }
}