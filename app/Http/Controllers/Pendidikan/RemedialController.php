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
}