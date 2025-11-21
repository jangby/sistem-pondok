<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

class AnggotaKelasController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    // 1. Daftar Kelas (Mustawa)
    public function index()
    {
        $pondokId = $this->getPondokId();
        
        // Ambil Mustawa beserta jumlah santrinya
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->withCount('santris as total_santri') // Asumsi di model Mustawa ada relasi santris()
            ->orderBy('tingkat')
            ->get();

        return view('pendidikan.admin.anggota-kelas.index', compact('mustawas'));
    }

    // 2. Detail Anggota Kelas & Form Tambah
    // 2. Detail Anggota & Form Tambah (Dengan Pencarian)
    public function show(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($id);

        // List Santri yang SUDAH ada di kelas ini
        $members = Santri::where('mustawa_id', $id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        // List Santri yang BELUM punya kelas (Non-Mustawa)
        // + Fitur Pencarian
        $query = Santri::where('pondok_id', $pondokId)
                       ->where('status', 'active')
                       ->whereNull('mustawa_id');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $nonKelas = $query->orderBy('full_name')->paginate(20); // Pakai paginate biar tidak berat jika santri ribuan

        return view('pendidikan.admin.anggota-kelas.show', compact('mustawa', 'members', 'nonKelas'));
    }

    // 3. Proses Tambah Anggota (Bulk / Banyak Sekaligus)
    public function store(Request $request, $id)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id',
        ], [
            'santri_ids.required' => 'Pilih setidaknya satu santri untuk ditambahkan.',
        ]);

        $mustawa = Mustawa::findOrFail($id);

        // Update Massal (Satu kali query untuk banyak data)
        Santri::whereIn('id', $request->santri_ids)->update([
            'mustawa_id' => $id
        ]);

        $jumlah = count($request->santri_ids);
        return back()->with('success', "Berhasil menambahkan $jumlah santri ke kelas {$mustawa->nama}.");
    }

    // 4. Proses Keluarkan Anggota dari Kelas
    public function destroy($id, $santri_id)
    {
        $santri = Santri::where('mustawa_id', $id)->findOrFail($santri_id);
        
        // Set null
        $santri->update(['mustawa_id' => null]);

        return back()->with('success', 'Santri dikeluarkan dari kelas.');
    }

    // --- FITUR KENAIKAN KELAS ---

    public function promotionIndex()
    {
        $pondokId = $this->getPondokId();
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();
        
        return view('pendidikan.admin.anggota-kelas.promotion', compact('mustawas'));
    }

    public function promotionCheck(Request $request)
    {
        $pondokId = $this->getPondokId();
        $sourceId = $request->source_mustawa_id;
        
        // Ambil santri dari kelas asal
        $santris = Santri::where('mustawa_id', $sourceId)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();
                         
        // TODO: Di sini nanti bisa di-load juga Nilai Rata-rata Ujian mereka jika fitur nilai sudah ada
        
        $sourceMustawa = Mustawa::find($sourceId);
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        return view('pendidikan.admin.anggota-kelas.promotion', compact('mustawas', 'santris', 'sourceMustawa'));
    }

    public function promotionStore(Request $request)
    {
        $request->validate([
            'target_mustawa_id' => 'required|exists:mustawas,id',
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id',
        ]);

        // Update massal
        Santri::whereIn('id', $request->santri_ids)->update([
            'mustawa_id' => $request->target_mustawa_id
        ]);

        return redirect()->route('pendidikan.admin.anggota-kelas.index')
                         ->with('success', count($request->santri_ids) . ' Santri berhasil dinaikkan kelasnya.');
    }
}