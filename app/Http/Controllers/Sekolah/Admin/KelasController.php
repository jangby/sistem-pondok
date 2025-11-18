<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas; //
use App\Models\Santri; //
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
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

    // Helper Keamanan: Cek apakah kelas ini milik sekolah si admin
    private function checkOwnership(Kelas $kelas)
    {
        $sekolah = $this->getSekolah();
        // Cek pondok_id DAN cek 'tingkat'
        if ($kelas->pondok_id != $sekolah->pondok_id || $kelas->tingkat != $sekolah->tingkat) {
            abort(404);
        }
    }

    // === FUNGSI CRUD ===

    public function index()
    {
        $sekolah = $this->getSekolah();
        
        // INI KUNCI FILTER: Ambil kelas HANYA yang tingkatannya = tingkatan sekolah si admin
        $kelasList = Kelas::where('pondok_id', $this->getPondokId())
                        ->where('tingkat', $sekolah->tingkat) //
                        ->orderBy('nama_kelas')->get();
                        
        return view('sekolah.admin.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        $sekolah = $this->getSekolah();
        // Kirim 'tingkat' ke view agar bisa di-readonly
        return view('sekolah.admin.kelas.create', ['tingkat' => $sekolah->tingkat]);
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();
        $sekolah = $this->getSekolah();

        $validated = $request->validate([
            'nama_kelas' => [
                'required', 'string', 'max:255',
                // Pastikan nama unik per pondok & per tingkat
                Rule::unique('kelas')->where(fn ($q) => 
                    $q->where('pondok_id', $pondokId)->where('tingkat', $sekolah->tingkat)
                )
            ],
            // 'tingkat' tidak perlu divalidasi, kita ambil paksa dari $sekolah
        ]);
        
        // Tambahkan pondok_id dan tingkat secara paksa
        $validated['pondok_id'] = $pondokId;
        $validated['tingkat'] = $sekolah->tingkat; //

        Kelas::create($validated); //
        
        return redirect()->route('sekolah.admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kela) // 'kela' untuk hindari bentrok
    {
        $this->checkOwnership($kela); // Keamanan
        return view('sekolah.admin.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $this->checkOwnership($kela); // Keamanan
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_kelas' => [
                'required', 'string', 'max:255',
                Rule::unique('kelas')->where(fn ($q) => 
                    $q->where('pondok_id', $pondokId)->where('tingkat', $kela->tingkat)
                )->ignore($kela->id)
            ],
            // 'tingkat' tidak bisa diubah
        ]);
        
        $kela->update($validated); //
        return redirect()->route('sekolah.admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $this->checkOwnership($kela); // Keamanan
        
        // TODO: Cek jika kelas masih punya santri
        
        $kela->delete(); //
        return redirect()->route('sekolah.admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    // === FUNGSI NAIK KELAS ===

    public function naikKelasView()
    {
        $sekolah = $this->getSekolah();
        // Ambil SEMUA kelas di pondok ini, tapi kelompokkan berdasarkan tingkat
        // agar view bisa membuat dropdown "Dari Kelas" dan "Ke Kelas"
        $kelasList = Kelas::where('pondok_id', $this->getPondokId())
                        ->orderBy('tingkat')->orderBy('nama_kelas')->get()
                        ->groupBy('tingkat');
                        
        // Ambil hanya kelas yang relevan dengan sekolah ini
        $kelasSekolahIni = $kelasList->get($sekolah->tingkat, collect()); //
        
        return view('sekolah.admin.kelas.naik-kelas', compact('kelasList', 'kelasSekolahIni', 'sekolah'));
    }

    public function naikKelasProcess(Request $request)
    {
        $sekolah = $this->getSekolah();
        
        $validated = $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ], [
            'kelas_asal_id.required' => 'Kelas asal wajib diisi.',
            'kelas_tujuan_id.required' => 'Kelas tujuan wajib diisi.',
        ]);

        $kelasAsal = Kelas::find($validated['kelas_asal_id']);
        $kelasTujuan = Kelas::find($validated['kelas_tujuan_id']);

        // Keamanan: Pastikan admin ini berhak memindahkan dari KELAS ASAL
        if ($kelasAsal->pondok_id != $sekolah->pondok_id || $kelasAsal->tingkat != $sekolah->tingkat) {
            return back()->with('error', 'Anda tidak berhak memindahkan santri dari kelas tersebut.');
        }
        // Keamanan: Pastikan KELAS TUJUAN ada di pondok yang sama
        if ($kelasTujuan->pondok_id != $sekolah->pondok_id) {
            return back()->with('error', 'Kelas tujuan tidak valid.');
        }

        try {
            // Ini adalah logika inti "Naik Kelas"
            $jumlahSantri = Santri::where('kelas_id', $validated['kelas_asal_id']) //
                                  ->update(['kelas_id' => $validated['kelas_tujuan_id']]); //

            return redirect()->route('sekolah.admin.kelas.index')
                             ->with('success', "Berhasil memindahkan $jumlahSantri santri dari kelas " 
                                . $kelasAsal->nama_kelas . " ke " . $kelasTujuan->nama_kelas . ".");
        
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail kelas (daftar santri)
     */
    public function show(Kelas $kela)
    {
        $this->checkOwnership($kela); // Keamanan

        // 1. Ambil daftar santri di kelas ini
        $santriDiKelas = $kela->santris()->with('orangTua')->orderBy('full_name')->get(); //

        // 2. Ambil daftar santri yang belum punya kelas (untuk dropdown tambah)
        // Kita hanya ambil santri aktif di pondok ini yang kelas_id-nya NULL
        $santriTanpaKelas = Santri::where('pondok_id', $this->getPondokId()) //
                                  ->where('status', 'active')
                                  ->whereNull('kelas_id')
                                  ->orderBy('full_name')
                                  ->get();
        
        // 3. Ambil daftar kelas lain (untuk dropdown pindah)
        $kelasList = Kelas::where('pondok_id', $this->getPondokId()) //
                        ->where('id', '!=', $kela->id) // Kecuali kelas ini sendiri
                        ->orderBy('tingkat')->orderBy('nama_kelas')->get()
                        ->groupBy('tingkat');
        
        return view('sekolah.admin.kelas.show', compact(
            'kela', 
            'santriDiKelas', 
            'santriTanpaKelas', 
            'kelasList'
        ));
    }

    /**
     * Tambahkan santri (dari 'Tanpa Kelas') ke kelas ini
     */
    public function addSantri(Request $request, Kelas $kela)
    {
        $this->checkOwnership($kela); // Keamanan

        $request->validate([
            'santri_id' => 'required|exists:santris,id',
        ]);

        $santri = Santri::find($request->santri_id); //

        // Keamanan tambahan: Cek apakah santri benar-benar milik pondok ini & belum punya kelas
        if ($santri->pondok_id == $this->getPondokId() && is_null($santri->kelas_id)) {
            $santri->update(['kelas_id' => $kela->id]); //
            return back()->with('success', $santri->full_name . ' berhasil ditambahkan ke kelas.');
        }

        return back()->with('error', 'Gagal menambahkan santri.');
    }

    /**
     * Pindahkan satu santri dari kelas ini ke kelas lain
     */
    public function moveSantri(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ]);

        // Keamanan: Pastikan admin ini berhak memindahkan santri ini
        // (Santri ini harus berasal dari kelas yang dikelola admin)
        $this->checkOwnership($santri->kelas); //

        $kelasTujuan = Kelas::find($validated['kelas_tujuan_id']); //
        
        // Keamanan: Pastikan kelas tujuan ada di pondok yang sama
        if ($kelasTujuan->pondok_id != $this->getPondokId()) {
            return back()->with('error', 'Kelas tujuan tidak valid.');
        }
        
        $santri->update(['kelas_id' => $validated['kelas_tujuan_id']]); //

        return back()->with('success', $santri->full_name . ' berhasil dipindahkan ke kelas ' . $kelasTujuan->nama_kelas . '.');
    }
}