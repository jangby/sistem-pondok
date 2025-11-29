<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Pondok; // Pastikan model ini di-import
use Carbon\Carbon;

class KartuUjianController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        return $user->pondokStaff ? $user->pondokStaff->pondok_id : $user->pondok_id;
    }

    public function index()
    {
        $pondokId = $this->getPondokId();
        $mustawas = Mustawa::where('pondok_id', $pondokId)
                           ->where('is_active', true)
                           ->orderBy('tingkat')
                           ->get();

        return view('pendidikan.admin.kartu.index', compact('mustawas'));
    }

    // [BARU] Method untuk API mengambil data santri berdasarkan kelas
    public function getSantriByMustawa($mustawaId)
    {
        $santris = Santri::where('mustawa_id', $mustawaId)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->select('id', 'full_name', 'nis') // Ambil field yg perlu saja
                         ->get();

        return response()->json($santris);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'mustawa_id' => 'required',
            'nama_ujian' => 'nullable|string',
            'santri_ids' => 'nullable|array', // [MODIFIKASI] Validasi array ID santri
        ]);

        // 1. Ambil Data Santri
        $query = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active');

        // [MODIFIKASI] Jika ada filter nama tertentu yang dipilih
        if ($request->filled('santri_ids')) {
            $query->whereIn('id', $request->santri_ids);
        }

        $santris = $query->orderBy('full_name')->get();

        if ($santris->isEmpty()) {
            return back()->with('error', 'Tidak ada santri yang dipilih atau ditemukan.');
        }

        // 2. Siapkan Data Judul Ujian
        $ujian = (object) [
            'nama' => $request->nama_ujian ?? 'UJIAN DINIYAH PESANTREN',
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1)
        ];

        // 3. Return View Cetak
        return view('pendidikan.admin.kartu.print', compact('santris', 'ujian'));
    }
}