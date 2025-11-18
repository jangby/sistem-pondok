<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\Lokasi;
use App\Models\Santri;

class BarangController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    // 1. HALAMAN UTAMA: GRUP LOKASI
    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Ambil Lokasi beserta jumlah barang dan total nilai aset di lokasi tsb
        $lokasis = Lokasi::where('pondok_id', $pondokId)
            ->withCount('items')
            ->with(['items' => function($q) {
                $q->select('id', 'location_id', 'price', 'qty_good', 'qty_damaged', 'qty_borrowed', 'qty_repairing');
            }])
            ->get()
            ->map(function($lokasi) {
                // Hitung estimasi nilai aset per lokasi
                $lokasi->total_nilai = $lokasi->items->sum(function($item) {
                    $totalQty = $item->qty_good + $item->qty_damaged + $item->qty_borrowed + $item->qty_repairing;
                    return $item->price * $totalQty;
                });
                return $lokasi;
            });

        return view('pengurus.inventaris.barang.index', compact('lokasis'));
    }

    // 2. HALAMAN DETAIL: LIST BARANG PER LOKASI
    public function byLokasi(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $lokasi = Lokasi::where('pondok_id', $pondokId)->findOrFail($id);

        $query = Barang::where('pondok_id', $pondokId)
            ->where('location_id', $id)
            ->with(['pic']);

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('code', 'like', '%'.$request->search.'%');
            });
        }

        $barangs = $query->latest()->paginate(15);
        
        // Data untuk Modal Tambah (Dropdown Santri)
        $santris = Santri::where('pondok_id', $pondokId)->where('status', 'active')->orderBy('full_name')->get();

        return view('pengurus.inventaris.barang.list', compact('lokasi', 'barangs', 'santris'));
    }

    // 3. SIMPAN BARANG (Redirectnya kembali ke lokasi tsb)
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:inv_items,code',
            'name' => 'required',
            'location_id' => 'required',
            'qty_good' => 'required|integer|min:0',
            'unit' => 'required',
            'price' => 'required|numeric',
        ]);

        Barang::create([
            'pondok_id' => $this->getPondokId(),
            'location_id' => $request->location_id,
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
            'price' => $request->price,
            'qty_good' => $request->qty_good,
            'qty_damaged' => 0, 'qty_repairing' => 0, 'qty_broken' => 0, 'qty_lost' => 0, 'qty_borrowed' => 0,
            'pic_santri_id' => $request->pic_santri_id
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $barang->update($request->only(['name', 'unit', 'price', 'pic_santri_id'])); 
        // Note: Location tidak diupdate disini agar konsisten list-nya
        
        return back()->with('success', 'Data barang diperbarui.');
    }

    public function destroy($id)
    {
        $barang = Barang::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $barang->delete();
        return back()->with('success', 'Barang dihapus.');
    }
}