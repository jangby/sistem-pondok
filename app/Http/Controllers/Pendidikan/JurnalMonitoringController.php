<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalMengajar;
use App\Models\Ustadz;
use App\Models\Mustawa;
use Carbon\Carbon;

class JurnalMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $pondokId = auth()->user()->pondokStaff->pondok_id ?? auth()->user()->pondok_id;

        // Filter
        $tanggalAwal = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $ustadzId = $request->input('ustadz_id');
        $mustawaId = $request->input('mustawa_id');

        // Query Data Jurnal Mengajar
        // Kita load relasi jadwal untuk tahu Mapel & Kelas
        $query = JurnalMengajar::whereHas('ustadz', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->with(['ustadz', 'jadwal.mapel', 'jadwal.mustawa'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($ustadzId) {
            $query->where('ustadz_id', $ustadzId);
        }

        if ($mustawaId) {
            // Filter berdasarkan jadwal -> mustawa
            $query->whereHas('jadwal', function($q) use ($mustawaId) {
                $q->where('mustawa_id', $mustawaId);
            });
        }

        $jurnals = $query->latest('tanggal')->paginate(20)->withQueryString();

        // Data untuk Dropdown Filter
        $ustadzs = Ustadz::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('nama_lengkap')->get();
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        return view('pendidikan.admin.monitoring.jurnal', compact(
            'jurnals', 'ustadzs', 'mustawas', 'tanggalAwal', 'tanggalAkhir'
        ));
    }
}