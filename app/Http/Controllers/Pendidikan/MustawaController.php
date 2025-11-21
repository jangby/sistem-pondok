<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use App\Models\Mustawa;
use App\Models\Ustadz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MustawaController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();

        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->with('waliUstadz')
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->paginate(10);

        return view('pendidikan.admin.mustawa.index', compact('mustawas'));
    }

    public function create()
    {
        $pondokId = $this->getPondokId();
        
        // Ambil data Ustadz untuk pilihan Wali Kelas
        $ustadzs = Ustadz::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('pendidikan.admin.mustawa.create', compact('ustadzs'));
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|integer|min:1|max:12', // Asumsi tingkat 1-12
            'gender' => 'required|in:putra,putri,campuran',
            'wali_ustadz_id' => 'nullable|exists:ustadzs,id',
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        // Tambahkan pondok_id ke data yang akan disimpan
        $validated['pondok_id'] = $pondokId;
        $validated['is_active'] = true;

        Mustawa::create($validated);

        return redirect()->route('pendidikan.admin.mustawa.index')
            ->with('success', 'Data Mustawa berhasil ditambahkan.');
    }

    public function edit(Mustawa $mustawa)
    {
        // Pastikan akses data milik sendiri
        if ($mustawa->pondok_id != $this->getPondokId()) {
            abort(403);
        }

        $ustadzs = Ustadz::where('pondok_id', $this->getPondokId())
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('pendidikan.admin.mustawa.edit', compact('mustawa', 'ustadzs'));
    }

    public function update(Request $request, Mustawa $mustawa)
    {
        if ($mustawa->pondok_id != $this->getPondokId()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|integer',
            'gender' => 'required|in:putra,putri,campuran',
            'wali_ustadz_id' => 'nullable|exists:ustadzs,id',
            'tahun_ajaran' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        $mustawa->update($validated);

        return redirect()->route('pendidikan.admin.mustawa.index')
            ->with('success', 'Data Mustawa berhasil diperbarui.');
    }

    public function destroy(Mustawa $mustawa)
    {
        if ($mustawa->pondok_id != $this->getPondokId()) {
            abort(403);
        }

        try {
            $mustawa->delete();
            return redirect()->route('pendidikan.admin.mustawa.index')
                ->with('success', 'Data Mustawa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Mungkin kelas ini masih memiliki jadwal atau santri.');
        }
    }
}