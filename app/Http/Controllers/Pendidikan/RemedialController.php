<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\MapelDiniyah;
use App\Models\NilaiPesantren;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RemedialController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function index(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // Data Filter
        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();
        $mapels = []; // Akan diisi jika mustawa dipilih
        
        $selectedMustawa = null;
        $selectedMapel = null;
        $remedialList = [];
        $kkm = 60; // Default

        if ($request->filled('mustawa_id')) {
            $mapels = MapelDiniyah::where('pondok_id', $pondokId)->orderBy('nama_mapel')->get();
            $selectedMustawa = Mustawa::find($request->mustawa_id);
        }

        if ($request->filled(['mustawa_id', 'mapel_diniyah_id', 'kategori', 'semester'])) {
            $selectedMapel = MapelDiniyah::find($request->mapel_diniyah_id);
            $kkm = $selectedMapel->kkm ?? 60;
            
            $colName = 'nilai_' . strtolower($request->kategori); // misal: nilai_hafalan
            
            // Query Santri Remedial
            $remedialList = NilaiPesantren::with('santri')
                ->where('mustawa_id', $request->mustawa_id)
                ->where('mapel_diniyah_id', $request->mapel_diniyah_id)
                ->where('semester', $request->semester)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->whereNotNull($colName)    // Pastikan sudah dinilai
                ->where($colName, '<', $kkm) // Cari yang dibawah KKM
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
        // Logika query sama persis dengan index, kita ambil datanya
        $pondokId = $this->getPondokId();
        $selectedMustawa = Mustawa::findOrFail($request->mustawa_id);
        $selectedMapel = MapelDiniyah::findOrFail($request->mapel_diniyah_id);
        $kkm = $selectedMapel->kkm ?? 60;
        
        $colName = 'nilai_' . strtolower($request->kategori);
        
        $remedialList = NilaiPesantren::with('santri')
            ->where('mustawa_id', $request->mustawa_id)
            ->where('mapel_diniyah_id', $request->mapel_diniyah_id)
            ->where('semester', $request->semester)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->whereNotNull($colName)
            ->where($colName, '<', $kkm)
            ->get()
            ->sortBy(function($query) {
                return $query->santri->full_name;
            });

        $pondok = auth()->user()->pondokStaff->pondok;
        $judul = "DAFTAR REMEDIAL - " . strtoupper($request->kategori);
        
        $pdf = Pdf::loadView('pendidikan.admin.remedial.pdf', compact(
            'remedialList', 'selectedMustawa', 'selectedMapel', 'kkm', 'pondok', 'judul', 'request'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Remedial_' . $selectedMapel->nama_mapel . '.pdf');
    }

    public function edit(Request $request, $id)
    {
        // Ambil data nilai berdasarkan ID
        $nilai = NilaiPesantren::with(['santri', 'mapel', 'mustawa'])->findOrFail($id);
        
        // Tentukan kategori dari request (default tulis jika tidak ada)
        $kategori = $request->query('kategori', 'tulis');
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

        $nilaiRecord = NilaiPesantren::findOrFail($id);
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