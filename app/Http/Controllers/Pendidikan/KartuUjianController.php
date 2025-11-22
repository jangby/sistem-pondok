<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mustawa;
use App\Models\Santri;
use App\Models\Pondok;
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
        // Ambil daftar kelas/mustawa
        $mustawas = Mustawa::where('pondok_id', $pondokId)
                           ->where('is_active', true)
                           ->orderBy('tingkat')
                           ->get();

        // Tidak perlu lagi ambil templates
        return view('pendidikan.admin.kartu.index', compact('mustawas'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'mustawa_id' => 'required',
            'nama_ujian' => 'nullable|string', // Input baru untuk judul kartu
        ]);

        // 1. Ambil Data Santri di Kelas Tersebut
        $santris = Santri::where('mustawa_id', $request->mustawa_id)
                         ->where('status', 'active') // Pastikan hanya santri aktif
                         ->orderBy('full_name') // Sesuaikan dengan kolom nama di database Anda (bisa 'nama' atau 'full_name')
                         ->get();

        if ($santris->isEmpty()) {
            return back()->with('error', 'Tidak ada santri di kelas yang dipilih.');
        }

        // 2. Siapkan Data Judul Ujian untuk Header Kartu
        $ujian = (object) [
            'nama' => $request->nama_ujian ?? 'UJIAN DINIYAH PESANTREN',
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1) // Default Tahun Ajaran (bisa disesuaikan)
        ];

        // 3. Langsung Return View Cetak (Desain Fixed Grid 2x4)
        // Pastikan file resources/views/pendidikan/admin/kartu/print.blade.php sudah pakai kode yang saya berikan sebelumnya
        return view('pendidikan.admin.kartu.print', compact('santris', 'ujian'));
    }
}