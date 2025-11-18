<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Kelas; // <-- Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SantriController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    /**
     * Menampilkan daftar santri.
     */
    public function index()
    {
        // Trait otomatis memfilter!
        $santris = Santri::with(['orangTua', 'kelas']) // Load relasi kelas
                        ->latest()
                        ->paginate(10);
                        
        return view('adminpondok.santris.index', compact('santris'));
    }

    /**
     * Menampilkan form tambah santri.
     */
    public function create()
    {
        // Trait otomatis memfilter data ini
        $orangTuas = OrangTua::orderBy('name')->get(); 
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();
        
        return view('adminpondok.santris.create', compact('orangTuas', 'daftarKelas'));
    }

    /**
     * Menyimpan santri baru.
     */
    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nis' => [
                'required', 'string', 'max:50',
                Rule::unique('santris')->where(fn ($q) => $q->where('pondok_id', $pondokId)),
            ],
            // --- DATA BARU ---
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'golongan_darah' => 'nullable|string|max:5',
            'riwayat_penyakit' => 'nullable|string',
            // -----------------
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:active,graduated,moved',
        ]);

        Santri::create($validated); // create() otomatis ambil semua key yg ada di $fillable
        
        return redirect()->route('adminpondok.santris.index')
                         ->with('success', 'Data santri lengkap berhasil ditambahkan.');
    }

    public function update(Request $request, Santri $santri)
    {
        if ($santri->pondok_id != $this->getPondokId()) abort(404);
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nis' => [
                'required', 'string', 'max:50',
                Rule::unique('santris')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($santri->id),
            ],
            // --- DATA BARU ---
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'golongan_darah' => 'nullable|string|max:5',
            'riwayat_penyakit' => 'nullable|string',
            // -----------------
            'orang_tua_id' => 'required|exists:orang_tuas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:active,graduated,moved',
        ]);

        $santri->update($validated);

        return redirect()->route('adminpondok.santris.index')
                         ->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Menampilkan form edit santri.
     * INI FUNGSI PERBAIKANNYA
     */
    public function edit(Santri $santri) // Gunakan Route-Model Binding
    {
        // 1. Keamanan: Cek apakah santri ini milik pondok-nya
        if ($santri->pondok_id != $this->getPondokId()) {
            abort(404);
        }

        // 2. Ambil data untuk dropdown
        $orangTuas = OrangTua::orderBy('name')->get(); 
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();
        
        // 3. Kirim semua data (termasuk $santri) ke view
        return view('adminpondok.santris.edit', compact('santri', 'orangTuas', 'daftarKelas'));
    }

    /**
     * Hapus data santri.
     */
    public function destroy(Santri $santri)
    {
        // Keamanan
        if ($santri->pondok_id != $this->getPondokId()) {
            abort(404);
        }

        try {
            if ($santri->tagihans()->count() > 0) {
                return redirect()->route('adminpondok.santris.index')
                                 ->with('error', 'Gagal! Santri ini masih memiliki data tagihan.');
            }
            $santriName = $santri->full_name;
            $santri->delete();
            return redirect()->route('adminpondok.santris.index')
                             ->with('success', "Data santri '$santriName' berhasil dihapus.");

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.santris.index')
                             ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail santri (untuk generate tagihan masa depan)
     */
    public function show(Santri $santri)
    {
        // Keamanan
        if ($santri->pondok_id != $this->getPondokId()) {
            abort(404);
        }
        
        $santri->load('orangTua', 'kelas');
        
        $jenisPembayarans = \App\Models\JenisPembayaran::where('pondok_id', $this->getPondokId())
                            ->orderBy('name')
                            ->get();
                            
        return view('adminpondok.santris.show', compact('santri', 'jenisPembayarans'));
    }
}