<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Kerusakan;
use App\Models\Inventaris\Barang;
use Illuminate\Support\Facades\DB;

class KerusakanController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        $damages = Kerusakan::where('pondok_id', $this->getPondokId())
            ->with('barang')
            ->latest()
            ->paginate(10);
            
        $barangs = Barang::where('pondok_id', $this->getPondokId())->get(); // Untuk dropdown

        return view('pengurus.inventaris.kerusakan.index', compact('damages', 'barangs'));
    }

    // LAPOR RUSAK
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'qty' => 'required|integer|min:1',
            'severity' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $barang = Barang::findOrFail($request->item_id);

            // Cek Stok Bagus Cukup Gak?
            if ($barang->qty_good < $request->qty) {
                throw new \Exception("Stok bagus tidak cukup untuk dilaporkan rusak.");
            }

            // 1. Buat Laporan Kerusakan
            Kerusakan::create([
                'pondok_id' => $this->getPondokId(),
                'item_id' => $request->item_id,
                'qty' => $request->qty,
                'severity' => $request->severity,
                'status' => 'dilaporkan',
                'date_reported' => now(),
                'notes' => $request->notes
            ]);

            // 2. Update Stok Barang (Pindahkan dari Good ke Damaged)
            $barang->decrement('qty_good', $request->qty);
            $barang->increment('qty_damaged', $request->qty);
        });

        return back()->with('success', 'Kerusakan berhasil dilaporkan. Stok disesuaikan.');
    }

    // PENYELESAIAN (RESOLVE)
    public function resolve(Request $request, $id)
    {
        $request->validate(['action' => 'required|in:perbaiki,selesai_fix,ganti,buang']);
        
        $damage = Kerusakan::findOrFail($id);
        $barang = $damage->barang;

        DB::transaction(function () use ($request, $damage, $barang) {
            
            if ($request->action == 'perbaiki') {
                // Pindahkan dari Damaged ke Repairing
                $damage->update(['status' => 'diperbaiki']);
                $barang->decrement('qty_damaged', $damage->qty);
                $barang->increment('qty_repairing', $damage->qty);
            
            } elseif ($request->action == 'selesai_fix') {
                // Pindahkan dari Repairing ke Good (Sembuh)
                $damage->update(['status' => 'selesai', 'date_resolved' => now()]);
                $barang->decrement('qty_repairing', $damage->qty);
                $barang->increment('qty_good', $damage->qty);

            } elseif ($request->action == 'ganti') {
                // Barang diganti baru (Stok rusak hilang, stok bagus nambah dari pembelian baru)
                // Kita asumsi 'ganti' berarti rusak dibuang, dan ada barang baru masuk
                $damage->update(['status' => 'selesai', 'date_resolved' => now(), 'notes' => $damage->notes . ' (Diganti Baru)']);
                $barang->decrement('qty_damaged', $damage->qty);
                $barang->increment('qty_good', $damage->qty); // Barang pengganti masuk

            } elseif ($request->action == 'buang') {
                // Barang rusak total (Broken)
                $damage->update(['status' => 'selesai', 'date_resolved' => now(), 'notes' => $damage->notes . ' (Rusak Total/Dibuang)']);
                $barang->decrement('qty_damaged', $damage->qty);
                $barang->increment('qty_broken', $damage->qty);
            }
        });

        return back()->with('success', 'Status kerusakan diperbarui.');
    }
}