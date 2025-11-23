<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Barang;
use App\Models\Inventaris\Lokasi;
use App\Models\Santri;
use Picqer\Barcode\BarcodeGeneratorPNG; // Tambahan library barcode
use Illuminate\Support\Str; // Helper string

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

    // 3. SIMPAN BARANG (Auto Generate Kode jika kosong)
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|unique:inv_items,code', // Ubah jadi nullable agar bisa auto-generate
            'name' => 'required',
            'location_id' => 'required',
            'qty_good' => 'required|integer|min:0',
            'unit' => 'required',
            'price' => 'required|numeric',
        ]);

        // Logika Auto Generate Kode
        $kodeBarang = $request->code;
        if (empty($kodeBarang)) {
            // Format: INV-{TAHUN}-{URUTAN_ID}
            // Kita ambil ID terakhir secara global untuk urutan unik
            $latestBarang = Barang::latest()->first();
            $nextId = $latestBarang ? $latestBarang->id + 1 : 1;
            $kodeBarang = 'INV-' . date('Y') . '-' . sprintf('%04d', $nextId);
        }

        Barang::create([
            'pondok_id' => $this->getPondokId(),
            'location_id' => $request->location_id,
            'code' => $kodeBarang, // Gunakan kode hasil generate/input user
            'name' => $request->name,
            'unit' => $request->unit,
            'price' => $request->price,
            'qty_good' => $request->qty_good,
            'qty_damaged' => 0, 'qty_repairing' => 0, 'qty_broken' => 0, 'qty_lost' => 0, 'qty_borrowed' => 0,
            'pic_santri_id' => $request->pic_santri_id
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan dengan Kode: ' . $kodeBarang);
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

   // 4. DOWNLOAD LABEL BARCODE (PER LOKASI)
    public function printLabels($id) // Tambahkan parameter $id
    {
        $pondokId = $this->getPondokId();
        
        // Validasi: Pastikan lokasi tersebut milik pondok ini
        $lokasi = Lokasi::where('pondok_id', $pondokId)->findOrFail($id);

        // Filter barang berdasarkan pondok_id DAN location_id
        $barangs = Barang::where('pondok_id', $pondokId)
                         ->where('location_id', $id) // Filter Lokasi
                         ->whereNotNull('code')
                         ->where('code', '!=', '')
                         ->get();
        
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        $pdf = app('dompdf.wrapper');
        
        // Kita kirim data lokasi juga untuk judul PDF jika perlu
        $pdf->loadView('pengurus.inventaris.barang.pdf_labels', compact('barangs', 'generator', 'lokasi'));
        
        $pdf->setPaper('A4', 'portrait');

        // Nama file dinamis
        return $pdf->stream('label-barang-' . \Illuminate\Support\Str::slug($lokasi->name) . '.pdf');
    }
}