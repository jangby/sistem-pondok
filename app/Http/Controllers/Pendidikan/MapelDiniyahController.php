<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\MapelDiniyah;
use Illuminate\Http\Request;

class MapelDiniyahController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();

        $query = MapelDiniyah::where('pondok_id', $pondokId);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kitab', 'like', "%{$search}%")
                  ->orWhere('nama_mapel', 'like', "%{$search}%");
            });
        }

        $mapels = $query->orderBy('nama_mapel')->paginate(10);

        return view('pendidikan.admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('pendidikan.admin.mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kitab' => 'required|string|max:255',
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:20',
            'kkm' => 'required|integer|min:0|max:100',
            // Checkbox tidak dikirim jika tidak dicentang, jadi validasi boolean cukup
            'uji_tulis' => 'nullable', 
            'uji_lisan' => 'nullable',
            'uji_praktek' => 'nullable',
            'uji_hafalan' => 'nullable',

        ]);

        $validated['pondok_id'] = $this->getPondokId();
        
        // Konversi checkbox ke boolean
        $validated['uji_tulis'] = $request->has('uji_tulis');
        $validated['uji_lisan'] = $request->has('uji_lisan');
        $validated['uji_praktek'] = $request->has('uji_praktek');
        $validated['uji_hafalan'] = $request->has('uji_hafalan');

        MapelDiniyah::create($validated);

        return redirect()->route('pendidikan.admin.mapel.index')
            ->with('success', 'Data Kitab/Mapel berhasil ditambahkan.');
    }

    public function edit(MapelDiniyah $mapel)
    {
        if ($mapel->pondok_id != $this->getPondokId()) abort(403);
        
        return view('pendidikan.admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MapelDiniyah $mapel)
    {
        if ($mapel->pondok_id != $this->getPondokId()) abort(403);

        $validated = $request->validate([
            'nama_kitab' => 'required|string|max:255',
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:20',
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        // Update data dengan konversi checkbox
        $mapel->update([
            'nama_kitab' => $request->nama_kitab,
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
            'kkm' => $request->kkm,
            'uji_tulis' => $request->has('uji_tulis'),
            'uji_lisan' => $request->has('uji_lisan'),
            'uji_praktek' => $request->has('uji_praktek'),
            'uji_hafalan' => $request->has('uji_hafalan'),
        ]);

        return redirect()->route('pendidikan.admin.mapel.index')
            ->with('success', 'Data Kitab berhasil diperbarui.');
    }

    public function destroy(MapelDiniyah $mapel)
    {
        if ($mapel->pondok_id != $this->getPondokId()) abort(403);

        try {
            $mapel->delete();
            return redirect()->route('pendidikan.admin.mapel.index')
                ->with('success', 'Data Kitab berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Mapel ini mungkin sudah digunakan di jadwal atau nilai.');
        }
    }
}