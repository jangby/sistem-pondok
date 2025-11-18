<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah; // <-- Gunakan Model yang sudah kita buat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SekolahController extends Controller
{
    // Helper untuk mengambil Pondok ID
    private function getPondokId()
    {
        // Ambil pondok_id dari Super Admin Sekolah yang sedang login
        return Auth::user()->pondokStaff->pondok_id; //
    }
    
    // Helper untuk Keamanan
    private function checkOwnership(Sekolah $sekolah)
    {
        if ($sekolah->pondok_id != $this->getPondokId()) {
            abort(404);
        }
    }

    /**
     * Tampilkan daftar unit sekolah
     */
    public function index()
    {
        // Trait BelongsToPondok di model Sekolah
        // akan otomatis memfilter data berdasarkan pondok_id super admin sekolah.
        $sekolahs = Sekolah::latest()->paginate(10);
        
        return view('sekolah.superadmin.sekolah.index', compact('sekolahs'));
    }

    /**
     * Tampilkan form tambah unit sekolah
     */
    public function create()
    {
        return view('sekolah.superadmin.sekolah.create');
    }

    /**
     * Simpan unit sekolah baru
     */
    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_sekolah' => [
                'required', 'string', 'max:255',
                // Pastikan nama sekolah unik per pondok
                Rule::unique('sekolahs')->where(fn ($q) => $q->where('pondok_id', $pondokId)),
            ],
            'tingkat' => 'required|string|max:50',
            'kepala_sekolah' => 'nullable|string|max:255',
        ]);

        // Tambahkan pondok_id dan simpan
        // Model Sekolah menggunakan trait BelongsToPondok,
        // jadi pondok_id akan terisi otomatis.
        // Siapkan data untuk disimpan
        $dataToSave = $validated;
        
        // AMBIL PONDOK ID SECARA MANUAL DARI HELPER KITA
        $dataToSave['pondok_id'] = $this->getPondokId(); //

        // Simpan data
        Sekolah::create($dataToSave); //

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Unit Sekolah berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(Sekolah $sekolah)
    {
        $this->checkOwnership($sekolah); // Keamanan
        return view('sekolah.superadmin.sekolah.edit', compact('sekolah'));
    }

    /**
     * Update data unit sekolah
     */
    public function update(Request $request, Sekolah $sekolah)
    {
        $this->checkOwnership($sekolah); // Keamanan
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_sekolah' => [
                'required', 'string', 'max:255',
                Rule::unique('sekolahs')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($sekolah->id),
            ],
            'tingkat' => 'required|string|max:50',
            'kepala_sekolah' => 'nullable|string|max:255',
        ]);

        $sekolah->update($validated);

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Unit Sekolah berhasil diperbarui.');
    }

    /**
     * Hapus data unit sekolah
     */
    public function destroy(Sekolah $sekolah)
    {
        $this->checkOwnership($sekolah); // Keamanan
        
        // TODO: Nanti tambahkan pengecekan jika sekolah masih punya data (guru, jadwal, dll)
        
        $sekolah->delete();

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Unit Sekolah berhasil dihapus.');
    }
}