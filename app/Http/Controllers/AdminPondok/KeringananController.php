<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\Keringanan;
use App\Models\Santri; // <-- IMPORT
use App\Models\JenisPembayaran; // <-- IMPORT
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // <-- IMPORT

class KeringananController extends Controller
{
    /**
     * Menampilkan daftar aturan keringanan.
     */
    public function index()
    {
        // Trait otomatis memfilter
        // Eager load relasi santri & jenisPembayaran
        $keringanans = Keringanan::with(['santri', 'jenisPembayaran'])
                                ->latest()
                                ->paginate(10);
                                
        return view('adminpondok.keringanan.index', compact('keringanans'));
    }

    /**
     * Menampilkan form tambah aturan keringanan baru.
     */
    public function create()
    {
        // Trait otomatis memfilter data ini
        $santris = Santri::where('status', 'active')->orderBy('full_name')->get();
        $jenisPembayarans = JenisPembayaran::orderBy('name')->get();

        // Cek jika data master belum ada
        if ($santris->isEmpty()) {
            return redirect()->route('adminpondok.santris.index')
                             ->with('error', 'Gagal! Silakan tambahkan Data Santri terlebih dahulu.');
        }
        if ($jenisPembayarans->isEmpty()) {
            return redirect()->route('adminpondok.jenis-pembayarans.index')
                             ->with('error', 'Gagal! Silakan tambahkan Jenis Pembayaran terlebih dahulu.');
        }

        return view('adminpondok.keringanan.create', compact('santris', 'jenisPembayarans'));
    }

    /**
     * Menyimpan aturan keringanan baru.
     */
    public function store(Request $request)
    {
        $pondokId = auth()->user()->pondokStaff->pondok_id;

        $validated = $request->validate([
            'santri_id' => [
                'required',
                'exists:santris,id',
                // Aturan unik: 1 santri hanya boleh punya 1 aturan per jenis pembayaran
                Rule::unique('keringanans')->where(function ($query) use ($request, $pondokId) {
                    return $query->where('pondok_id', $pondokId)
                                 ->where('jenis_pembayaran_id', $request->jenis_pembayaran_id);
                }),
            ],
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayarans,id',
            'tipe_keringanan' => 'required|in:persentase,nominal_tetap',
            'nilai' => 'required|numeric|min:0',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_mulai',
            'keterangan' => 'nullable|string|max:255',
        ], [
            // Pesan custom untuk error validasi unik
            'santri_id.unique' => 'Santri ini sudah memiliki aturan keringanan untuk jenis pembayaran tersebut.'
        ]);

        // Trait otomatis mengisi pondok_id
        Keringanan::create($validated);

        return redirect()->route('adminpondok.keringanans.index')
                         ->with('success', 'Aturan keringanan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit aturan keringanan.
     */
    public function edit(Keringanan $keringanan)
    {
        // Trait otomatis mengamankan
        $santris = Santri::where('status', 'active')->orderBy('full_name')->get();
        $jenisPembayarans = JenisPembayaran::orderBy('name')->get();

        return view('adminpondok.keringanan.edit', compact('keringanan', 'santris', 'jenisPembayarans'));
    }

    /**
     * Update aturan keringanan di database.
     */
    public function update(Request $request, Keringanan $keringanan)
    {
        // Trait otomatis mengamankan
        $pondokId = auth()->user()->pondokStaff->pondok_id;

        $validated = $request->validate([
            'santri_id' => [
                'required',
                'exists:santris,id',
                // Validasi unik, KECUALI untuk dirinya sendiri
                Rule::unique('keringanans')->where(function ($query) use ($request, $pondokId) {
                    return $query->where('pondok_id', $pondokId)
                                 ->where('jenis_pembayaran_id', $request->jenis_pembayaran_id);
                })->ignore($keringanan->id),
            ],
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayarans,id',
            'tipe_keringanan' => 'required|in:persentase,nominal_tetap',
            'nilai' => 'required|numeric|min:0',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_mulai',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'santri_id.unique' => 'Santri ini sudah memiliki aturan keringanan untuk jenis pembayaran tersebut.'
        ]);

        $keringanan->update($validated);

        return redirect()->route('adminpondok.keringanans.index')
                         ->with('success', 'Aturan keringanan berhasil diperbarui.');
    }

    /**
     * Hapus aturan keringanan.
     */
    public function destroy(Keringanan $keringanan)
    {
        // Trait otomatis mengamankan
        try {
            $keringanan->delete();
            return redirect()->route('adminpondok.keringanans.index')
                             ->with('success', 'Aturan keringanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('adminpondok.keringanans.index')
                             ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Biarkan show() kosong, tidak kita pakai
    public function show(Keringanan $keringanan)
    {
        //
    }
}