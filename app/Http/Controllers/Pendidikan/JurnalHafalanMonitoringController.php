<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalPendidikan;
use App\Models\Ustadz;
use App\Models\Mustawa;
use Carbon\Carbon;

class JurnalHafalanMonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID Pondok
        $user = auth()->user();
        $pondokId = $user->pondokStaff->pondok_id ?? $user->pondok_id;

        // Default Tanggal (Awal bulan s/d Hari ini)
        $tanggalAwal = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $ustadzId = $request->input('ustadz_id');
        $mustawaId = $request->input('mustawa_id');
        $namaSantri = $request->input('nama_santri');

        // Query Data
        $query = JurnalPendidikan::query()
            ->where('jenis', 'hafalan') // Filter khusus hafalan
            ->whereHas('ustadz', function($q) use ($pondokId) {
                $q->where('pondok_id', $pondokId);
            })
            ->with(['ustadz', 'santri', 'santri.mustawa'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        // Filter Lanjutan
        if ($ustadzId) {
            $query->where('ustadz_id', $ustadzId);
        }

        if ($mustawaId) {
            $query->whereHas('santri', function($q) use ($mustawaId) {
                $q->where('mustawa_id', $mustawaId);
            });
        }

        if ($namaSantri) {
            $query->whereHas('santri', function($q) use ($namaSantri) {
                $q->where('nama_lengkap', 'like', '%' . $namaSantri . '%')
                  ->orWhere('full_name', 'like', '%' . $namaSantri . '%');
            });
        }

        $jurnals = $query->latest('tanggal')->latest('created_at')->paginate(20)->withQueryString();

        // Data Dropdown
        $ustadzs = Ustadz::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('nama_lengkap')->get();
        $mustawas = Mustawa::where('pondok_id', $pondokId)->orderBy('tingkat')->get();

        return view('pendidikan.admin.monitoring.hafalan', compact(
            'jurnals', 'ustadzs', 'mustawas', 'tanggalAwal', 'tanggalAkhir'
        ));
    }
}