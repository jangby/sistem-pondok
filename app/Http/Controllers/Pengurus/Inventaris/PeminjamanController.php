<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Peminjaman;
use App\Models\Inventaris\Barang;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        $loans = Peminjaman::where('pondok_id', $this->getPondokId())->with('barang')->latest()->paginate(10);
        $barangs = Barang::where('pondok_id', $this->getPondokId())->get();
        return view('pengurus.inventaris.peminjaman.index', compact('loans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'borrower_name' => 'required',
            'qty' => 'required|integer|min:1',
            'end_date' => 'required|date'
        ]);

        DB::transaction(function () use ($request) {
            $barang = Barang::findOrFail($request->item_id);
            
            if ($barang->qty_good < $request->qty) throw new \Exception("Stok tidak cukup.");

            Peminjaman::create([
                'pondok_id' => $this->getPondokId(),
                'item_id' => $request->item_id,
                'borrower_name' => $request->borrower_name,
                'destination' => $request->destination,
                'qty' => $request->qty,
                'start_date' => now(),
                'end_date' => $request->end_date,
                'status' => 'active'
            ]);

            // Mutasi Stok
            $barang->decrement('qty_good', $request->qty);
            $barang->increment('qty_borrowed', $request->qty);
        });

        return back()->with('success', 'Peminjaman tercatat.');
    }

    public function kembali($id)
    {
        DB::transaction(function () use ($id) {
            $loan = Peminjaman::findOrFail($id);
            if ($loan->status != 'active') return;

            $loan->update(['status' => 'returned', 'return_date' => now()]);

            // Kembalikan Stok
            $barang = $loan->barang;
            $barang->decrement('qty_borrowed', $loan->qty);
            $barang->increment('qty_good', $loan->qty);
        });

        return back()->with('success', 'Barang telah dikembalikan.');
    }
}