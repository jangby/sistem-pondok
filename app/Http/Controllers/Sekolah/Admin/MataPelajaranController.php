<?php
namespace App\Http\Controllers\Sekolah\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    /**
     * Helper untuk mengambil data sekolah yang dikelola admin ini
     */
    private function getSekolah()
    {
        $adminUser = Auth::user();
        $sekolah = $adminUser->sekolahs()->first(); //
        if (!$sekolah) {
            abort(403, 'Akun Anda tidak ditugaskan ke unit sekolah manapun.');
        }
        return $sekolah;
    }

    /**
     * Helper Keamanan: Cek apakah mapel ini milik sekolah si admin
     */
    private function checkOwnership(MataPelajaran $mataPelajaran)
    {
        if ($mataPelajaran->sekolah_id != $this->getSekolah()->id) {
            abort(404);
        }
    }

    /**
     * Tampilkan daftar Mata Pelajaran
     */
    public function index()
    {
        $sekolah = $this->getSekolah();
        
        $mataPelajarans = MataPelajaran::where('sekolah_id', $sekolah->id) //
            ->latest()
            ->paginate(10);
            
        return view('sekolah.admin.mata-pelajaran.index', compact('mataPelajarans'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        return view('sekolah.admin.mata-pelajaran.create');
    }

    /**
     * Simpan Mata Pelajaran baru
     */
    public function store(Request $request)
    {
        $sekolah = $this->getSekolah();

        $validated = $request->validate([
            'nama_mapel' => [
                'required', 'string', 'max:255',
                // Pastikan nama mapel unik di sekolah ini
                Rule::unique('mata_pelajarans')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id)),
            ],
            'kode_mapel' => [
                'nullable', 'string', 'max:50',
                Rule::unique('mata_pelajarans')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id)),
            ],
        ]);

        // Tambahkan sekolah_id dan simpan
        $validated['sekolah_id'] = $sekolah->id;
        MataPelajaran::create($validated); //

        return redirect()->route('sekolah.admin.mata-pelajaran.index')
                         ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        $this->checkOwnership($mataPelajaran); // Keamanan
        return view('sekolah.admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update data Mata Pelajaran
     */
    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $this->checkOwnership($mataPelajaran); // Keamanan
        $sekolah = $this->getSekolah();

        $validated = $request->validate([
            'nama_mapel' => [
                'required', 'string', 'max:255',
                Rule::unique('mata_pelajarans')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id))->ignore($mataPelajaran->id),
            ],
            'kode_mapel' => [
                'nullable', 'string', 'max:50',
                Rule::unique('mata_pelajarans')->where(fn ($q) => $q->where('sekolah_id', $sekolah->id))->ignore($mataPelajaran->id),
            ],
        ]);

        $mataPelajaran->update($validated); //

        return redirect()->route('sekolah.admin.mata-pelajaran.index')
                         ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Hapus data Mata Pelajaran
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        $this->checkOwnership($mataPelajaran); // Keamanan
        
        // TODO: Nanti tambahkan pengecekan jika mapel sudah dipakai di jadwal
        
        $mataPelajaran->delete(); //

        return redirect()->route('sekolah.admin.mata-pelajaran.index')
                         ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}