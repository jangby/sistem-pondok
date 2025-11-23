<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Kelas; // Pastikan Model Kelas di-import
use Illuminate\Support\Facades\DB;

class AnggotaKelasController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->withCount('santris as total_santri')
            ->orderBy('tingkat')
            ->get();

        return view('pendidikan.admin.anggota-kelas.index', compact('mustawas'));
    }

    public function show(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($id);

        // 1. List Santri yang SUDAH ada di kelas ini
        $members = Santri::with('kelas')
                         ->where('mustawa_id', $id)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->get();

        // 2. Ambil Data Kelas Sekolah untuk Dropdown Filter
        // PERBAIKAN: Menggunakan 'nama_kelas' bukan 'nama'
        $dataKelasSekolah = Kelas::where('pondok_id', $pondokId)
                                 ->orderBy('nama_kelas') 
                                 ->get();

        // 3. Query Kandidat Santri (Yang BELUM masuk kelas diniyah manapun)
        $query = Santri::with('kelas')
                       ->where('pondok_id', $pondokId)
                       ->where('status', 'active')
                       ->whereNull('mustawa_id'); // PENTING: Hanya santri non-kelas yang muncul

        // Filter Otomatis Gender (Sesuai gender Mustawa)
        if (!empty($mustawa->gender)) {
            $genderMustawa = strtolower($mustawa->gender);
            $targetGender = null;
            if (in_array($genderMustawa, ['l', 'laki-laki', 'putra', 'male'])) $targetGender = 'L';
            elseif (in_array($genderMustawa, ['p', 'perempuan', 'putri', 'female'])) $targetGender = 'P';

            if ($targetGender) {
                $query->where('jenis_kelamin', $targetGender);
            }
        }

        // Filter Manual Kelas Sekolah (Dari Dropdown)
        if ($request->filled('kelas_filter')) {
            $query->where('kelas_id', $request->kelas_filter);
        }

        // Pencarian Nama/NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $nonKelas = $query->orderBy('full_name')
                          ->paginate(20)
                          ->withQueryString();

        return view('pendidikan.admin.anggota-kelas.show', compact('mustawa', 'members', 'nonKelas', 'dataKelasSekolah'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id',
        ], [
            'santri_ids.required' => 'Pilih setidaknya satu santri.',
        ]);

        $mustawa = Mustawa::findOrFail($id);

        Santri::whereIn('id', $request->santri_ids)->update([
            'mustawa_id' => $id
        ]);

        return back()->with('success', count($request->santri_ids) . " Santri berhasil ditambahkan.");
    }

    public function destroy($id, $santri_id)
    {
        $santri = Santri::where('mustawa_id', $id)->findOrFail($santri_id);
        $santri->update(['mustawa_id' => null]);
        return back()->with('success', 'Santri dikeluarkan dari kelas.');
    }
}