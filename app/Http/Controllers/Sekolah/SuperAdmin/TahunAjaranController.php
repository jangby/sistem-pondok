<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\TahunAjaran; // <-- Gunakan Model yang sudah kita buat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Import DB
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    // Helper untuk mengambil Pondok ID
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id; //
    }

    // Helper untuk Keamanan
    private function checkOwnership(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->pondok_id != $this->getPondokId()) {
            abort(404);
        }
    }

    /**
     * Tampilkan daftar Tahun Ajaran
     */
    public function index()
    {
        // Ambil data dan filter manual (karena trait BelongsToPondok tidak berlaku)
        $tahunAjarans = TahunAjaran::where('pondok_id', $this->getPondokId()) //
            ->latest()
            ->paginate(10);
            
        return view('sekolah.superadmin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    /**
     * Tampilkan form tambah
     */
    public function create()
    {
        return view('sekolah.superadmin.tahun-ajaran.create');
    }

    /**
     * Simpan Tahun Ajaran baru
     */
    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_tahun_ajaran' => [
                'required', 'string', 'max:255',
                Rule::unique('tahun_ajarans')->where(fn ($q) => $q->where('pondok_id', $pondokId)),
            ],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // Tambahkan pondok_id secara manual (karena trait tidak berlaku)
        $validated['pondok_id'] = $pondokId;

        TahunAjaran::create($validated); //

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran); // Keamanan
        return view('sekolah.superadmin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update data Tahun Ajaran
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran); // Keamanan
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_tahun_ajaran' => [
                'required', 'string', 'max:255',
                Rule::unique('tahun_ajarans')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($tahunAjaran->id),
            ],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $tahunAjaran->update($validated); //

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    /**
     * Hapus data Tahun Ajaran
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran); // Keamanan
        
        // TODO: Nanti tambahkan pengecekan jika tahun ajaran sudah dipakai di jadwal/nilai
        
        $tahunAjaran->delete(); //

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
    
    /**
     * FUNGSI BARU: Aktifkan satu Tahun Ajaran dan nonaktifkan yang lain
     */
    public function activate(TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran); // Keamanan
        $pondokId = $this->getPondokId();

        DB::transaction(function () use ($tahunAjaran, $pondokId) {
            // 1. Nonaktifkan semua tahun ajaran di pondok ini
            TahunAjaran::where('pondok_id', $pondokId)
                ->where('is_active', true)
                ->update(['is_active' => false]); //
            
            // 2. Aktifkan yang dipilih
            $tahunAjaran->update(['is_active' => true]); //
        });

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', $tahunAjaran->nama_tahun_ajaran . ' berhasil diaktifkan.');
    }
}