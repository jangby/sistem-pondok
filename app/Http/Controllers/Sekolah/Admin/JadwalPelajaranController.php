<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\JadwalPelajaran;
use App\Models\Sekolah\Sekolah;
use App\Models\Sekolah\MataPelajaran;
use App\Models\Sekolah\TahunAjaran;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JadwalPelajaranController extends Controller
{
    // === HELPER FUNCTIONS ===
    private function getSekolah()
    {
        $adminUser = Auth::user();
        // Asumsi relasi user ke sekolahs benar
        $sekolah = $adminUser->sekolahs()->first(); 
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return $sekolah;
    }
    
    private function getPondokId()
    {
        // Pastikan user memiliki relasi pondokStaff
        return Auth::user()->pondokStaff->pondok_id; 
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
                            ->first();
    }

    // === DROPDOWN DATA HELPER (PENTING) ===
    // Helper ini memastikan kita hanya mengambil data milik sekolah/pondok ini saja
    private function getFormData()
    {
        $sekolah = $this->getSekolah();
        $pondokId = $this->getPondokId();

        // Ambil Kelas milik pondok ini
        $kelasList = Kelas::where('pondok_id', $pondokId)
                        ->orderBy('tingkat')
                        ->orderBy('nama_kelas')
                        ->get(); 
        
        // Ambil Mapel milik sekolah ini
        $mapelList = MataPelajaran::where('sekolah_id', $sekolah->id)
                        ->orderBy('nama_mapel')
                        ->get(); 
        
        // Ambil Guru yang terdaftar di pondok ini
        // (Disarankan menggunakan spatie/permission role, atau kolom role manual)
        $guruList = User::role('guru') 
                    ->whereHas('pondokStaff', fn($q) => $q->where('pondok_id', $pondokId))
                    ->orderBy('name')
                    ->get();
                    
        return compact('kelasList', 'mapelList', 'guruList');
    }

    /**
     * Tampilkan daftar Jadwal Pelajaran
     * (Sekarang mengirim data dropdown juga untuk Modal)
     */
    public function index(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();
        
        // [UPDATE] Ambil data referensi menggunakan helper yang aman
        $formData = $this->getFormData();
        $kelasList = $formData['kelasList'];
        $mapelList = $formData['mapelList'];
        $guruList  = $formData['guruList'];
        
        // Query Dasar
        $jadwalQuery = JadwalPelajaran::where('sekolah_id', $sekolah->id);
        
        // Filter Tahun Ajaran
        if ($tahunAjaranAktif) {
             $jadwalQuery->where('tahun_ajaran_id', $tahunAjaranAktif->id);
        } else {
            // Jika tidak ada tahun ajaran aktif, jangan tampilkan apa-apa untuk keamanan
            $jadwalQuery->where('id', -1); 
        }

        // Fitur Pencarian (Search) dari Action Bar
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $jadwalQuery->where(function($q) use ($search) {
                $q->whereHas('guru', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('mataPelajaran', function($q2) use ($search) {
                    $q2->where('nama_mapel', 'like', "%{$search}%");
                })
                ->orWhereHas('kelas', function($q2) use ($search) {
                    $q2->where('nama_kelas', 'like', "%{$search}%");
                });
            });
        }
           
        $jadwals = $jadwalQuery->with(['kelas', 'mataPelajaran', 'guru']) // Eager load relasi
            ->orderBy('kelas_id')
            // Sorting Custom Hari (Senin -> Minggu)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->paginate(20)
            ->withQueryString(); // Agar pagination tetap membawa parameter search
            
        return view('sekolah.admin.jadwal-pelajaran.index', compact(
            'jadwals', 
            'tahunAjaranAktif', 
            'kelasList', 
            'mapelList', 
            'guruList'
        ));
    }

    /**
     * Simpan Jadwal Pelajaran baru
     */
    public function store(Request $request)
    {
        $sekolah = $this->getSekolah();
        $tahunAjaranAktif = $this->getTahunAjaranAktif();

        if (!$tahunAjaranAktif) {
            return back()->withErrors(['msg' => 'Sesi Tahun Ajaran Aktif berakhir. Gagal menyimpan.']);
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
        
        // Opsional: Cek bentrok jadwal (Guru yang sama di jam yang sama)
        // $isBentrok = JadwalPelajaran::where('guru_user_id', $request->guru_user_id)
        //     ->where('hari', $request->hari)
        //     ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
        //     ->where(function($q) use ($request) {
        //         $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
        //           ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
        //     })->exists();
        // if($isBentrok) return back()->withErrors(['guru_user_id' => 'Guru ini sudah mengajar di jam tersebut!']);

        JadwalPelajaran::create($validated);

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil ditambahkan.');
    }

    /**
     * Update data Jadwal Pelajaran
     */
    public function update(Request $request, JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'guru_user_id' => 'required|exists:users,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);
        
        $jadwalPelajaran->update($validated);

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus data Jadwal Pelajaran
     */
    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        $this->checkOwnership($jadwalPelajaran);
        
        $jadwalPelajaran->delete();

        return redirect()->route('sekolah.admin.jadwal-pelajaran.index')
                         ->with('success', 'Jadwal Pelajaran berhasil dihapus.');
    }
    
    // Method create() dan edit() bisa dihapus atau dibiarkan kosong 
    // karena kita sudah menggunakan Modal di index.
}