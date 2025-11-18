<?php

namespace App\Http\Controllers\Pengurus\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventaris\Lokasi;

class LokasiController extends Controller
{
    private function getPondokId() { return Auth::user()->pondokStaff->pondok_id; }

    public function index()
    {
        $lokasis = Lokasi::where('pondok_id', $this->getPondokId())->withCount('items')->get();
        return view('pengurus.inventaris.lokasi.index', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Lokasi::create([
            'pondok_id' => $this->getPondokId(),
            'name' => $request->name,
            'description' => $request->description
        ]);
        return back()->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $lokasi->update($request->only(['name', 'description']));
        return back()->with('success', 'Lokasi diperbarui.');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::where('pondok_id', $this->getPondokId())->findOrFail($id);
        $lokasi->delete();
        return back()->with('success', 'Lokasi dihapus.');
    }
}