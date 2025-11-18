<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use App\Models\ItemPembayaran;    // Model yang dikelola controller ini
use App\Models\JenisPembayaran; // Model Induk (Parent)
use Illuminate\Http\Request;

class ItemPembayaranController extends Controller
{
    /**
     * Menampilkan daftar item (tidak kita gunakan,
     * karena daftar item ada di halaman show JenisPembayaran).
     * Kita redirect ke halaman show induknya.
     * URL: /adminpondok/jenis-pembayarans/{jenisPembayaran}/items
     */
    public function index(JenisPembayaran $jenisPembayaran)
    {
        // Trait 'BelongsToPondok' pada 'JenisPembayaran' sudah mengamankan.
        // Redirect ke halaman detail induknya
        return redirect()->route('adminpondok.jenis-pembayarans.show', $jenisPembayaran->id);
    }

    /**
     * Menampilkan form untuk membuat item baru.
     * URL: /adminpondok/jenis-pembayarans/{jenisPembayaran}/items/create
     */
    public function create(JenisPembayaran $jenisPembayaran)
    {
        // Trait 'BelongsToPondok' pada 'JenisPembayaran' sudah mengamankan.
        // Kirim $jenisPembayaran ke view agar form tahu induknya
        return view('adminpondok.items.create', compact('jenisPembayaran'));
    }

    /**
     * Menyimpan item baru ke database.
     * URL: POST /adminpondok/jenis-pembayarans/{jenisPembayaran}/items
     */
    public function store(Request $request, JenisPembayaran $jenisPembayaran)
    {
        // Trait 'BelongsToPondok' pada 'JenisPembayaran' sudah mengamankan.

        $validated = $request->validate([
            'nama_item' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'prioritas' => 'required|integer|min:1',
        ]);

        // Buat item baru menggunakan relasi
        // Ini otomatis mengisi 'jenis_pembayaran_id'
        $jenisPembayaran->items()->create([
            'nama_item' => $validated['nama_item'],
            'nominal' => $validated['nominal'],
            'prioritas' => $validated['prioritas'],
            // 'pondok_id' akan diisi otomatis oleh Trait 'BelongsToPondok'
        ]);

        // Redirect kembali ke halaman detail induknya
        return redirect()->route('adminpondok.jenis-pembayarans.show', $jenisPembayaran->id)
                         ->with('success', 'Item rincian berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu item (tidak kita gunakan,
     * kita redirect ke halaman show induknya).
     * URL: /adminpondok/items/{item}
     */
    public function show(ItemPembayaran $item)
    {
        // Trait 'BelongsToPondok' pada 'ItemPembayaran' sudah mengamankan.
        // Redirect ke halaman detail induknya
        return redirect()->route('adminpondok.jenis-pembayarans.show', $item->jenis_pembayaran_id);
    }

    /**
     * Menampilkan form untuk mengedit item (menggunakan rute shallow).
     * URL: /adminpondok/items/{item}/edit
     */
    public function edit(ItemPembayaran $item)
    {
        // Trait 'BelongsToPondok' pada 'ItemPembayaran' sudah mengamankan.
        
        // Kirim data 'item' ke view
        return view('adminpondok.items.edit', compact('item'));
    }

    /**
     * Memperbarui item di database (menggunakan rute shallow).
     * URL: PUT/PATCH /adminpondok/items/{item}
     */
    public function update(Request $request, ItemPembayaran $item)
    {
        // Trait 'BelongsToPondok' pada 'ItemPembayaran' sudah mengamankan.
        
        $validated = $request->validate([
            'nama_item' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'prioritas' => 'required|integer|min:1',
        ]);

        $item->update($validated);

        // Redirect kembali ke halaman detail induknya
        return redirect()->route('adminpondok.jenis-pembayarans.show', $item->jenis_pembayaran_id)
                         ->with('success', 'Item rincian berhasil diperbarui.');
    }

    /**
     * Menghapus item dari database (menggunakan rute shallow).
     * URL: DELETE /adminpondok/items/{item}
     */
    public function destroy(ItemPembayaran $item)
    {
        // Trait 'BelongsToPondok' pada 'ItemPembayaran' sudah mengamankan.

        try {
            // Nanti kita tambahkan cek jika item sudah dipakai di tagihan

            $itemName = $item->nama_item;
            $parentJenisPembayaranId = $item->jenis_pembayaran_id;
            
            $item->delete();

            return redirect()->route('adminpondok.jenis-pembayarans.show', $parentJenisPembayaranId)
                             ->with('success', "Item '$itemName' berhasil dihapus.");

        } catch (\Exception $e) {
            return redirect()->route('adminpondok.jenis-pembayarans.show', $item->jenis_pembayaran_id)
                             ->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }
}