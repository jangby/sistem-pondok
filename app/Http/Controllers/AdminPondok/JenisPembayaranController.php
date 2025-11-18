<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- IMPORT INI
use App\Models\Kelas; // <-- Pastikan ini ada

class JenisPembayaranController extends Controller
{
    /**
     * Menampilkan daftar jenis pembayaran (SPP, Uang Gedung, dll)
     */
    public function index()
    {
        // Trait otomatis memfilter
        // Eager load 'items' untuk menghitung total nominal
        $jenisPembayarans = JenisPembayaran::with('items')->latest()->paginate(10);
        return view('adminpondok.jenispembayaran.index', compact('jenisPembayarans'));
    }

    /**
     * Menampilkan form tambah baru.
     */
    public function create()
    {
        return view('adminpondok.jenispembayaran.create');
    }

    /**
     * Menyimpan jenis pembayaran baru.
     */
    public function store(Request $request)
    {
        $pondokId = auth()->user()->pondokStaff->pondok_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan 'name' unik HANYA untuk pondok ini
                Rule::unique('jenis_pembayarans')->where(function ($query) use ($pondokId) {
                    return $query->where('pondok_id', $pondokId);
                }),
            ],
            'tipe' => 'required|in:bulanan,semesteran,tahunan,sekali_bayar',
        ]);

        // Trait otomatis mengisi pondok_id
        JenisPembayaran::create($validated);

        return redirect()->route('adminpondok.jenis-pembayarans.index')
                         ->with('success', 'Jenis Pembayaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail (untuk melihat/mengelola item-itemnya).
     */
    public function show(JenisPembayaran $jenisPembayaran)
    {
        // Trait otomatis mengamankan
        // Eager load items yang diurutkan berdasarkan prioritas
        $jenisPembayaran->load(['items' => function ($query) {
            $query->orderBy('prioritas', 'asc');
        }]);


        $daftarKelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

    return view('adminpondok.jenispembayaran.show', compact('jenisPembayaran', 'daftarKelas'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(JenisPembayaran $jenisPembayaran)
    {
        // Trait otomatis mengamankan
        return view('adminpondok.jenispembayaran.edit', compact('jenisPembayaran'));
    }

    /**
     * Update data di database.
     */
    public function update(Request $request, JenisPembayaran $jenisPembayaran)
    {
        // Trait otomatis mengamankan
        $pondokId = auth()->user()->pondokStaff->pondok_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan 'name' unik, KECUALI untuk dirinya sendiri
                Rule::unique('jenis_pembayarans')->where(function ($query) use ($pondokId) {
                    return $query->where('pondok_id', $pondokId);
                })->ignore($jenisPembayaran->id),
            ],
            'tipe' => 'required|in:bulanan,semesteran,tahunan,sekali_bayar',
        ]);

        $jenisPembayaran->update($validated);

        if ($request->has('kelas_ids')) {
        $jenisPembayaran->kelas()->sync($request->kelas_ids);
    } else {
        // Jika tidak ada yg dicek, hapus semua relasi
        $jenisPembayaran->kelas()->sync([]);
    }

    return redirect()->route('adminpondok.jenis-pembayarans.index')
                     ->with('success', 'Jenis Pembayaran berhasil diperbarui.');
    }

    /**
     * Hapus data.
     */
    public function destroy(JenisPembayaran $jenisPembayaran)
    {
        // Trait otomatis mengamankan
        try {
            // Cek jika masih punya item (uang makan, dll)
            if ($jenisPembayaran->items()->count() > 0) {
                return redirect()->route('adminpondok.jenis-pembayarans.index')
                                 ->with('error', 'Gagal! Hapus dulu item rincian (uang makan, listrik, dll) di dalamnya.');
            }
            // Cek jika masih dipakai di tagihan
            if ($jenisPembayaran->tagihans()->count() > 0) {
                return redirect()->route('adminpondok.jenis-pembayarans.index')
                                 ->with('error', 'Gagal! Jenis pembayaran ini sudah digunakan di data tagihan.');
            }

            $nama = $jenisPembayaran->name;
            $jenisPembayaran->delete();
            
            return redirect()->route('adminpondok.jenis-pembayarans.index')
                             ->with('success', "Jenis Pembayaran '$nama' berhasil dihapus.");

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.jenis-pembayarans.index')
                             ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}