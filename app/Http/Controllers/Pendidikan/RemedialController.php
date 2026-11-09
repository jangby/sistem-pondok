<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\NilaiPesantren;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Models\Pondok;

class RemedialController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        $pondokId = $user->pondokStaff?->pondok_id ?? $user->pondok_id;
        abort_unless($pondokId, 403, 'Akun pendidikan tidak terhubung dengan pondok.');

        return $pondokId;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'mustawa_id' => [
                'nullable',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
            'mapel_diniyah_id' => [
                'nullable',
                Rule::exists('mapel_diniyahs', 'id')->where('pondok_id', $pondokId),
            ],
            'kategori' => 'nullable|in:tulis,lisan,praktek,hafalan',
            'semester' => 'nullable|in:ganjil,genap',
            'tahun_ajaran' => 'nullable|string|max:20',
        ]);
        
        // Data Filter
        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();
        $mapels = []; // Akan diisi jika mustawa dipilih
        
        $selectedMustawa = null;
        $selectedMapel = null;
        $remedialList = [];
        $kkm = 60; // Default

        if ($request->filled('mustawa_id')) {
            $mapels = MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get();
            $selectedMustawa = Mustawa::where('pondok_id', $pondokId)
                ->findOrFail($validated['mustawa_id']);
        }

        if ($request->filled(['mustawa_id', 'mapel_diniyah_id', 'kategori', 'semester'])) {
            $selectedMapel = MapelDiniyah::where('pondok_id', $pondokId)
                ->findOrFail($validated['mapel_diniyah_id']);
            $kkm = $selectedMapel->kkm ?? 60;
            
            $colName = 'nilai_' . $validated['kategori'];
            
            $remedialList = NilaiPesantren::with('santri')
                ->whereHas('mustawa', fn ($query) => $query->where('pondok_id', $pondokId))
                ->whereHas('santri', fn ($query) => $query->where('pondok_id', $pondokId))
                ->where('mustawa_id', $validated['mustawa_id'])
                ->where('mapel_diniyah_id', $validated['mapel_diniyah_id'])
                ->where('semester', $validated['semester'])
                ->where('tahun_ajaran', $validated['tahun_ajaran'] ?? '')
                ->whereNotNull($colName)
                ->where($colName, '<', $kkm)
                ->get()
                ->sortBy(function($query) {
                    return $query->santri->full_name;
                });
        }

        return view('pendidikan.admin.remedial.index', compact(
            'mustawas', 'mapels', 'remedialList', 'selectedMustawa', 'selectedMapel', 'kkm'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'mustawa_id' => [
                'required',
                Rule::exists('mustawas', 'id')->where('pondok_id', $pondokId),
            ],
            'mapel_diniyah_id' => [
                'required',
                Rule::exists('mapel_diniyahs', 'id')->where('pondok_id', $pondokId),
            ],
            'kategori' => 'required|in:tulis,lisan,praktek,hafalan',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string|max:20',
        ]);
        $selectedMustawa = Mustawa::where('pondok_id', $pondokId)->findOrFail($validated['mustawa_id']);
        $selectedMapel = MapelDiniyah::where('pondok_id', $pondokId)->findOrFail($validated['mapel_diniyah_id']);
        $kkm = $selectedMapel->kkm ?? 60;
        
        $colName = 'nilai_' . $validated['kategori'];
        
        $remedialList = NilaiPesantren::with('santri')
            ->whereHas('mustawa', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereHas('santri', fn ($query) => $query->where('pondok_id', $pondokId))
            ->where('mustawa_id', $selectedMustawa->id)
            ->where('mapel_diniyah_id', $selectedMapel->id)
            ->where('semester', $validated['semester'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->whereNotNull($colName)
            ->where($colName, '<', $kkm)
            ->get()
            ->sortBy(function($query) {
                return $query->santri->full_name;
            });

        $pondok = Pondok::findOrFail($pondokId);
        $judul = "DAFTAR REMEDIAL - " . strtoupper($validated['kategori']);
        
        $pdf = Pdf::loadView('pendidikan.admin.remedial.pdf', compact(
            'remedialList', 'selectedMustawa', 'selectedMapel', 'kkm', 'pondok', 'judul', 'request'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Remedial_' . $selectedMapel->nama_mapel . '.pdf');
    }

    public function edit(Request $request, $id)
    {
        $pondokId = $this->getPondokId();
        $kategori = $request->validate(['kategori' => 'nullable|in:tulis,lisan,praktek,hafalan'])['kategori'] ?? 'tulis';
        $nilai = NilaiPesantren::with(['santri', 'mapel', 'mustawa'])
            ->whereHas('mustawa', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereHas('mapel', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereHas('santri', fn ($query) => $query->where('pondok_id', $pondokId))
            ->findOrFail($id);
        $colName = 'nilai_' . strtolower($kategori);
        
        // Nilai saat ini (sebelum remedial)
        $nilaiLama = $nilai->$colName;
        $kkm = $nilai->mapel->kkm ?? 60;

        return view('pendidikan.admin.remedial.edit', compact('nilai', 'kategori', 'nilaiLama', 'kkm'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai_baru' => 'required|numeric|min:0|max:100',
            'kategori' => 'required|in:tulis,lisan,praktek,hafalan',
        ]);

        $pondokId = $this->getPondokId();
        $nilaiRecord = NilaiPesantren::whereHas('mustawa', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereHas('mapel', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereHas('santri', fn ($query) => $query->where('pondok_id', $pondokId))
            ->with(['mapel', 'mustawa'])
            ->findOrFail($id);
        $kategori = strtolower($request->kategori);
        $kkm = $nilaiRecord->mapel->kkm ?? 60;
        
        $inputNilai = $request->nilai_baru;

        // --- ATURAN PENILAIAN REMEDIAL ---
        // Jika opsi "Batasi KKM" dicentang, maka nilai tidak boleh lebih dari KKM
        if ($request->has('batasi_kkm') && $inputNilai > $kkm) {
            $finalNilai = $kkm;
        } else {
            $finalNilai = $inputNilai;
        }

        // 1. Update Kolom Spesifik
        if ($kategori == 'tulis') {
            $nilaiRecord->nilai_tulis = $finalNilai;
        } elseif ($kategori == 'lisan') {
            $nilaiRecord->nilai_lisan = $finalNilai;
        } elseif ($kategori == 'praktek') {
            $nilaiRecord->nilai_praktek = $finalNilai;
        } elseif ($kategori == 'hafalan') {
            $nilaiRecord->nilai_hafalan = $finalNilai;
        }

        // 2. KALKULASI ULANG NILAI AKHIR (RATA-RATA)
        // Kita copy logika dari JadwalUjianController agar konsisten
        $mapel = $nilaiRecord->mapel;
        $components = 0; 
        $sum = 0;

        // Cek Tulis
        if($nilaiRecord->nilai_tulis !== null || $mapel->uji_tulis) { 
           $sum += $nilaiRecord->nilai_tulis ?? 0; $components++; 
        }
        // Cek Lisan
        if($nilaiRecord->nilai_lisan !== null || $mapel->uji_lisan) { 
           $sum += $nilaiRecord->nilai_lisan ?? 0; $components++; 
        }
        // Cek Praktek
        if($nilaiRecord->nilai_praktek !== null || $mapel->uji_praktek) { 
           $sum += $nilaiRecord->nilai_praktek ?? 0; $components++; 
        }
        // Cek Hafalan
        if($nilaiRecord->nilai_hafalan !== null || $mapel->uji_hafalan) { 
           $sum += $nilaiRecord->nilai_hafalan ?? 0; $components++; 
        }
        // Cek Kehadiran
        if($nilaiRecord->nilai_kehadiran !== null && $nilaiRecord->nilai_kehadiran > 0) { 
           $sum += $nilaiRecord->nilai_kehadiran; $components++; 
        }

        $nilaiRecord->nilai_akhir = $components > 0 ? ($sum / $components) : 0;
        $nilaiRecord->save();

        return redirect()->route('pendidikan.admin.monitoring.remedial.index', [
            'mustawa_id' => $nilaiRecord->mustawa_id,
            'mapel_diniyah_id' => $nilaiRecord->mapel_diniyah_id,
            'kategori' => $kategori,
            'semester' => $nilaiRecord->semester,
            'tahun_ajaran' => $nilaiRecord->tahun_ajaran // Menjaga filter tetap aktif
        ])->with('success', 'Nilai remedial berhasil disimpan & dikalkulasi ulang.');
    }
}