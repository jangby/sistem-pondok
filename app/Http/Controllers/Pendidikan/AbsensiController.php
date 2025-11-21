<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiDiniyah;
use App\Models\Mustawa;
use App\Models\Santri;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    private function getPondokId()
    {
        return auth()->user()->pondokStaff->pondok_id;
    }

    public function rekap(Request $request)
    {
        $pondokId = $this->getPondokId();
        
        // 1. Ambil Filter
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d')); // Default hari ini
        $mustawaId = $request->input('mustawa_id');

        // 2. Query Dasar
        $query = AbsensiDiniyah::query()
            ->whereHas('jadwal', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->whereDate('tanggal', $tanggal)
            ->with(['santri', 'jadwal.mustawa', 'jadwal.mapel', 'jadwal.ustadz']);

        // Filter Kelas jika ada
        if ($mustawaId) {
            $query->whereHas('jadwal', function($q) use ($mustawaId) {
                $q->where('mustawa_id', $mustawaId);
            });
        }

        // 3. Hitung Statistik (Eager Loading untuk performa)
        $statsData = (clone $query)->get(); // Clone query agar tidak ganggu query utama
        
        $totalAbsensi = $statsData->count();
        $hadir = $statsData->where('status', 'H')->count();
        $izin = $statsData->where('status', 'I')->count();
        $sakit = $statsData->where('status', 'S')->count();
        $alpha = $statsData->where('status', 'A')->count();

        // Persentase Kehadiran
        $persentase = $totalAbsensi > 0 ? round(($hadir / $totalAbsensi) * 100, 1) : 0;

        // 4. Data List (Paginate)
        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // 5. Data Pendukung Filter
        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();

        return view('pendidikan.admin.absensi.rekap', compact(
            'logs', 
            'mustawas', 
            'tanggal', 
            'hadir', 'izin', 'sakit', 'alpha', 'totalAbsensi', 'persentase'
        ));
    }
}