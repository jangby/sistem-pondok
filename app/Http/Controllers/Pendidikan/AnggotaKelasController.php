<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Kelas; // Pastikan Model Kelas di-import
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AnggotaKelasController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    private function normalizeGender(?string $gender): string
    {
        return match (strtolower(trim((string) $gender))) {
            'putra', 'putera', 'l', 'laki-laki', 'male' => 'putra',
            'putri', 'puteri', 'p', 'perempuan', 'female' => 'putri',
            default => 'campuran',
        };
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

    // 1. List Santri yang SUDAH ada di kelas ini (Tidak berubah)
    $members = Santri::with('kelas')
                     ->where('pondok_id', $pondokId)
                     ->where('mustawa_id', $id)
                     // Hapus filter status 'active' di sini jika ingin melihat siswa non-aktif yg terlanjur masuk
                     ->orderBy('full_name')
                     ->get();

    // 2. Dropdown Filter Kelas Sekolah (Tidak berubah)
    $dataKelasSekolah = Kelas::where('pondok_id', $pondokId)
                             ->orderBy('nama_kelas') 
                             ->get();

    // 3. Query Kandidat Santri (PERBAIKAN DI SINI)
    $query = Santri::with('kelas')
                   ->where('pondok_id', $pondokId)
                   // Gunakan whereIn untuk menangani 'active', 'Aktif', atau 1
                   ->whereIn('status', ['active', 'Aktif', '1']); 

    // PERBAIKAN PENTING: Menangani NULL, angka 0, atau string kosong
    $query->where(function($q) {
        $q->whereNull('mustawa_id')
          ->orWhere('mustawa_id', 0)
          ->orWhere('mustawa_id', '');
    });

    // Filter Otomatis Gender (Sesuai gender Mustawa)
    if (!empty($mustawa->gender)) {
        $genderMustawa = strtolower($mustawa->gender);
        
        // Tentukan target pencarian, tapi lebih fleksibel
        if (in_array($genderMustawa, ['l', 'laki-laki', 'putra', 'male'])) {
            // Cari 'L' ATAU 'Laki-laki'
            $query->whereIn('jenis_kelamin', ['L', 'Laki-laki', 'Male']);
        } 
        elseif (in_array($genderMustawa, ['p', 'perempuan', 'putri', 'female'])) {
            // Cari 'P' ATAU 'Perempuan'
            $query->whereIn('jenis_kelamin', ['P', 'Perempuan', 'Female']);
        }
    }

    // Filter Manual Kelas Sekolah
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
        $pondokId = $this->getPondokId();
        $mustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($id);

        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => [
                'required',
                'distinct',
                Rule::exists('santris', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where(function ($query) {
                            $query->whereNull('mustawa_id')
                                ->orWhere('mustawa_id', 0)
                                ->orWhere('mustawa_id', '');
                        })
                        ->whereIn('status', ['active', 'Aktif', '1']);
                }),
            ],
        ], [
            'santri_ids.required' => 'Pilih setidaknya satu santri.',
        ]);

        Santri::where('pondok_id', $pondokId)
            ->where(function ($query) {
                $query->whereNull('mustawa_id')
                    ->orWhere('mustawa_id', 0)
                    ->orWhere('mustawa_id', '');
            })
            ->whereIn('id', $request->santri_ids)
            ->update([
                'mustawa_id' => $mustawa->id,
        ]);

        return back()->with('success', count($request->santri_ids) . " Santri berhasil ditambahkan.");
    }

    public function destroy($id, $santri_id)
    {
        $pondokId = $this->getPondokId();
        Mustawa::where('pondok_id', $pondokId)->findOrFail($id);

        $santri = Santri::where('pondok_id', $pondokId)
            ->where('mustawa_id', $id)
            ->findOrFail($santri_id);
        $santri->update(['mustawa_id' => null]);
        return back()->with('success', 'Santri dikeluarkan dari kelas.');
    }

    public function promotionIndex()
    {
        $pondokId = $this->getPondokId();
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('pendidikan.admin.anggota-kelas.promotion', compact('mustawas'));
    }

    public function promotionCheck(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'source_mustawa_id' => [
                'required',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
        ]);

        $sourceMustawa = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->findOrFail($validated['source_mustawa_id']);

        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $sourceGender = $this->normalizeGender($sourceMustawa->gender);
        $targetMustawas = $mustawas
            ->filter(function (Mustawa $targetMustawa) use ($sourceMustawa, $sourceGender) {
                if ($targetMustawa->tingkat <= $sourceMustawa->tingkat) {
                    return false;
                }

                $targetGender = $this->normalizeGender($targetMustawa->gender);

                return $targetGender === 'campuran'
                    || ($sourceGender !== 'campuran' && $targetGender === $sourceGender);
            })
            ->values();

        $santris = Santri::where('pondok_id', $pondokId)
            ->where('mustawa_id', $sourceMustawa->id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('pendidikan.admin.anggota-kelas.promotion', compact(
            'mustawas',
            'targetMustawas',
            'sourceMustawa',
            'santris'
        ));
    }

    public function promotionStore(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'source_mustawa_id' => [
                'required',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
            'target_mustawa_id' => [
                'required',
                'different:source_mustawa_id',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
            'santri_ids' => ['required', 'array', 'min:1'],
            'santri_ids.*' => ['required', 'integer', 'distinct'],
        ], [
            'santri_ids.required' => 'Pilih setidaknya satu santri untuk dinaikkan.',
            'santri_ids.min' => 'Pilih setidaknya satu santri untuk dinaikkan.',
        ]);

        $sourceMustawa = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->findOrFail($validated['source_mustawa_id']);
        $targetMustawa = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->findOrFail($validated['target_mustawa_id']);

        $sourceGender = $this->normalizeGender($sourceMustawa->gender);
        $targetGender = $this->normalizeGender($targetMustawa->gender);
        $isGenderCompatible = $targetGender === 'campuran'
            || ($sourceGender !== 'campuran' && $targetGender === $sourceGender);

        if ($targetMustawa->tingkat <= $sourceMustawa->tingkat || !$isGenderCompatible) {
            return back()
                ->withInput()
                ->withErrors(['target_mustawa_id' => 'Kelas tujuan harus tingkat lebih tinggi dan memiliki gender yang sesuai.']);
        }

        $santriIds = array_values(array_unique($validated['santri_ids']));
        $updatedCount = DB::transaction(function () use ($pondokId, $sourceMustawa, $targetMustawa, $santriIds) {
            $eligibleIds = Santri::where('pondok_id', $pondokId)
                ->where('mustawa_id', $sourceMustawa->id)
                ->where('status', 'active')
                ->whereIn('id', $santriIds)
                ->lockForUpdate()
                ->pluck('id');

            if ($eligibleIds->count() !== count($santriIds)) {
                return 0;
            }

            return Santri::where('pondok_id', $pondokId)
                ->where('mustawa_id', $sourceMustawa->id)
                ->where('status', 'active')
                ->whereIn('id', $santriIds)
                ->update(['mustawa_id' => $targetMustawa->id]);
        });

        if ($updatedCount !== count($santriIds)) {
            return back()
                ->withInput()
                ->withErrors(['santri_ids' => 'Sebagian santri tidak lagi terdaftar di kelas asal atau bukan milik pondok ini. Muat ulang daftar dan coba lagi.']);
        }

        return redirect()
            ->route('pendidikan.admin.kenaikan-kelas.index')
            ->with('success', count($santriIds) . " santri berhasil dinaikkan dari {$sourceMustawa->nama} ke {$targetMustawa->nama}.");
    }
}