<?php

namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\Sekolah;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SekolahController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        $query = Sekolah::where('pondok_id', $pondokId);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('kepala_sekolah', 'like', "%{$search}%") // Tambah pencarian kepala sekolah
                  ->orWhere('tingkat', 'like', "%{$search}%");
            });
        }

        $sekolahs = $query->orderBy('tingkat')->paginate(10)->withQueryString();

        $sekolahs->getCollection()->transform(function ($sekolah) use ($pondokId) {
            $sekolah->guru_count = User::role('guru')
                ->whereHas('sekolahs', fn($q) => $q->where('sekolahs.id', $sekolah->id))
                ->count();
            
            $sekolah->siswa_count = Santri::where('pondok_id', $pondokId)
                ->where('status', 'active')
                ->whereHas('kelas', fn($q) => $q->where('tingkat', $sekolah->tingkat))
                ->count();
                
            return $sekolah;
        });

        return view('sekolah.superadmin.sekolah.index', compact('sekolahs'));
    }

    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_sekolah'   => 'required|string|max:255',
            'tingkat'        => 'required|in:SD,MI,SMP,MTS,SMA,MA,SMK,Pondok',
            'kepala_sekolah' => 'nullable|string|max:255', // <-- Dikembalikan
            'alamat'         => 'nullable|string|max:500',
            'email'          => 'nullable|email|max:255',
            'no_telp'        => 'nullable|string|max:20',
        ]);

        $validated['pondok_id'] = $pondokId;

        Sekolah::create($validated);

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Unit Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        if ($sekolah->pondok_id != $this->getPondokId()) abort(403);

        $validated = $request->validate([
            'nama_sekolah'   => 'required|string|max:255',
            'tingkat'        => 'required|in:SD,MI,SMP,MTS,SMA,MA,SMK,Pondok',
            'kepala_sekolah' => 'nullable|string|max:255', // <-- Dikembalikan
            'alamat'         => 'nullable|string|max:500',
            'email'          => 'nullable|email|max:255',
            'no_telp'        => 'nullable|string|max:20',
        ]);

        $sekolah->update($validated);

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Data Sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        if ($sekolah->pondok_id != $this->getPondokId()) abort(403);
        $sekolah->delete();

        return redirect()->route('sekolah.superadmin.sekolah.index')
                         ->with('success', 'Unit Sekolah berhasil dihapus.');
    }
}