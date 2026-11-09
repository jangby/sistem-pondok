<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Pondok; // Pastikan model ini di-import
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class KartuUjianController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        $pondokId = $user->pondokStaff?->pondok_id ?? $user->pondok_id;
        abort_unless($pondokId, 403, 'Akun pendidikan tidak terhubung dengan pondok.');

        return $pondokId;
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
        $pondokId = $this->getPondokId();
        Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->findOrFail($mustawaId);

        $santris = Santri::where('pondok_id', $pondokId)
                         ->where('mustawa_id', $mustawaId)
                         ->where('status', 'active')
                         ->orderBy('full_name')
                         ->select('id', 'full_name', 'nis') // Ambil field yg perlu saja
                         ->get();

        return response()->json($santris);
    }

    public function generate(Request $request)
    {
        $pondokId = $this->getPondokId();
        $request->validate([
            'mustawa_id' => [
                'required',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
            'nama_ujian' => 'nullable|string',
            'santri_ids' => 'nullable|array',
            'santri_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('santris', 'id')->where(function ($query) use ($request, $pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('mustawa_id', $request->input('mustawa_id'))
                        ->where('status', 'active');
                }),
            ],
        ]);

        Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->findOrFail($request->mustawa_id);

        $query = Santri::where('pondok_id', $pondokId)
                         ->where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active');

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