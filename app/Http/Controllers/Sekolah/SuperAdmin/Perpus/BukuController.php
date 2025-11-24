<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Perpus\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BukuController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $query = Buku::where('pondok_id', $this->getPondokId());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        $bukus = $query->latest()->paginate(10);
        return view('sekolah.superadmin.perpus.buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('sekolah.superadmin.perpus.buku.create');
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kode_buku' => [
                'nullable', // Jika kosong, kita generate otomatis
                'string', 
                'max:50',
                Rule::unique('perpus_bukus')->where(function ($query) use ($pondokId) {
                    return $query->where('pondok_id', $pondokId);
                })
            ],
            'stok_total' => 'required|integer|min:0',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|string|max:4',
            'isbn' => 'nullable|string|max:20',
            'lokasi_rak' => 'nullable|string|max:50',
        ]);

        // Auto-generate Kode Buku jika kosong (Format: LIB-TIMESTAMP-RANDOM)
        if (empty($validated['kode_buku'])) {
            $validated['kode_buku'] = 'LIB-' . time() . '-' . Str::upper(Str::random(3));
        }

        $validated['pondok_id'] = $pondokId;
        $validated['stok_tersedia'] = $validated['stok_total']; // Awal buat, tersedia = total

        Buku::create($validated);

        return redirect()->route('sekolah.superadmin.perpustakaan.buku.index')
                         ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        if ($buku->pondok_id != $this->getPondokId()) abort(404);
        return view('sekolah.superadmin.perpus.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        if ($buku->pondok_id != $this->getPondokId()) abort(404);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kode_buku' => [
                'required', 
                Rule::unique('perpus_bukus')->ignore($buku->id)->where(function ($query) use ($buku) {
                    return $query->where('pondok_id', $buku->pondok_id);
                })
            ],
            'stok_total' => 'required|integer|min:0',
            'penulis' => 'nullable|string',
            'penerbit' => 'nullable|string',
            'tahun_terbit' => 'nullable|string',
            'isbn' => 'nullable|string',
            'lokasi_rak' => 'nullable|string',
        ]);

        // Logic update stok:
        // Jika stok total ditambah, stok tersedia ikut nambah
        // Jika stok total dikurang, pastikan stok tersedia tidak minus (logic sederhana)
        $selisih = $validated['stok_total'] - $buku->stok_total;
        $validated['stok_tersedia'] = $buku->stok_tersedia + $selisih;

        if ($validated['stok_tersedia'] < 0) {
            return back()->with('error', 'Gagal update stok: Jumlah buku yang sedang dipinjam melebihi stok baru.');
        }

        $buku->update($validated);

        return redirect()->route('sekolah.superadmin.perpustakaan.buku.index')
                         ->with('success', 'Data buku diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->pondok_id != $this->getPondokId()) abort(404);
        
        // Cek apakah buku sedang dipinjam?
        if ($buku->stok_total != $buku->stok_tersedia) {
            return back()->with('error', 'Buku tidak bisa dihapus karena masih ada yang dipinjam.');
        }

        $buku->delete();
        return back()->with('success', 'Buku dihapus.');
    }
}