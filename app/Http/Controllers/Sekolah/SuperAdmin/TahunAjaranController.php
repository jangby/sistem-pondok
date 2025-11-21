<?php
namespace App\Http\Controllers\Sekolah\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    private function getPondokId()
    {
        return Auth::user()->pondokStaff->pondok_id;
    }

    private function checkOwnership(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->pondok_id != $this->getPondokId()) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // 1. Ambil Tahun Ajaran Aktif (Untuk Hero Section)
        $activeTahun = TahunAjaran::where('pondok_id', $pondokId)
                        ->where('is_active', true)
                        ->first();

        // 2. Query Data List (Searchable)
        $query = TahunAjaran::where('pondok_id', $pondokId);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tahun_ajaran', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru
        $tahunAjarans = $query->orderBy('tanggal_mulai', 'desc')
                              ->paginate(10)
                              ->withQueryString();
            
        return view('sekolah.superadmin.tahun-ajaran.index', compact('tahunAjarans', 'activeTahun'));
    }

    // Store & Update disesuaikan untuk redirect kembali ke index
    public function store(Request $request)
    {
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_tahun_ajaran' => [
                'required', 'string', 'max:255',
                Rule::unique('tahun_ajarans')->where(fn ($q) => $q->where('pondok_id', $pondokId)),
            ],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $validated['pondok_id'] = $pondokId;
        // Jika belum ada data sama sekali, jadikan otomatis aktif
        if (TahunAjaran::where('pondok_id', $pondokId)->count() == 0) {
            $validated['is_active'] = true;
        }

        TahunAjaran::create($validated);

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran);
        $pondokId = $this->getPondokId();

        $validated = $request->validate([
            'nama_tahun_ajaran' => [
                'required', 'string', 'max:255',
                Rule::unique('tahun_ajarans')->where(fn ($q) => $q->where('pondok_id', $pondokId))->ignore($tahunAjaran->id),
            ],
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $tahunAjaran->update($validated);

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran);
        
        if($tahunAjaran->is_active) {
            return back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus.');
        }

        $tahunAjaran->delete();

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
    
    public function activate(TahunAjaran $tahunAjaran)
    {
        $this->checkOwnership($tahunAjaran);
        $pondokId = $this->getPondokId();

        DB::transaction(function () use ($tahunAjaran, $pondokId) {
            TahunAjaran::where('pondok_id', $pondokId)->update(['is_active' => false]);
            $tahunAjaran->update(['is_active' => true]);
        });

        return redirect()->route('sekolah.superadmin.tahun-ajaran.index')
                         ->with('success', $tahunAjaran->nama_tahun_ajaran . ' sekarang AKTIF.');
    }
}