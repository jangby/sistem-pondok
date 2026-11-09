<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiDiniyah;
use App\Models\Mustawa;
use App\Models\Santri;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
{
    private function getPondokId()
    {
        $user = auth()->user();
        $pondokId = $user->pondokStaff?->pondok_id ?? $user->pondok_id;
        abort_unless($pondokId, 403, 'Akun pendidikan tidak terhubung dengan pondok.');

        return $pondokId;
    }

    private function logsQuery(int $pondokId, string $tanggal, ?int $mustawaId): Builder
    {
        return AbsensiDiniyah::query()
            ->whereHas('jadwal', function ($query) use ($pondokId, $mustawaId) {
                $query->where('pondok_id', $pondokId);
                if ($mustawaId) {
                    $query->where('mustawa_id', $mustawaId);
                }
            })
            ->whereHas('santri', fn ($query) => $query->where('pondok_id', $pondokId))
            ->whereDate('tanggal', $tanggal)
            ->with(['santri', 'jadwal.mustawa', 'jadwal.mapel', 'jadwal.ustadz']);
    }

    public function rekap(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'tanggal' => 'nullable|date_format:Y-m-d',
            'mustawa_id' => [
                'nullable',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
        ]);
        $tanggal = $validated['tanggal'] ?? Carbon::now()->format('Y-m-d');
        $mustawaId = isset($validated['mustawa_id']) ? (int) $validated['mustawa_id'] : null;
        $query = $this->logsQuery($pondokId, $tanggal, $mustawaId);

        $statsData = (clone $query)->get();
        
        $totalAbsensi = $statsData->count();
        $hadir = $statsData->where('status', 'H')->count();
        $izin = $statsData->where('status', 'I')->count();
        $sakit = $statsData->where('status', 'S')->count();
        $alpha = $statsData->where('status', 'A')->count();

        $persentase = $totalAbsensi > 0 ? round(($hadir / $totalAbsensi) * 100, 1) : 0;

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $mustawas = Mustawa::where('pondok_id', $pondokId)->where('is_active', true)->orderBy('tingkat')->get();

        return view('pendidikan.admin.absensi.rekap', compact(
            'logs', 
            'mustawas', 
            'tanggal', 
            'hadir', 'izin', 'sakit', 'alpha', 'totalAbsensi', 'persentase'
        ));
    }

    public function exportPdf(Request $request)
    {
        $pondokId = $this->getPondokId();
        $validated = $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'mustawa_id' => [
                'nullable',
                Rule::exists('mustawas', 'id')->where(function ($query) use ($pondokId) {
                    $query->where('pondok_id', $pondokId)
                        ->where('is_active', true);
                }),
            ],
        ]);

        $mustawaId = isset($validated['mustawa_id']) ? (int) $validated['mustawa_id'] : null;
        $logs = $this->logsQuery($pondokId, $validated['tanggal'], $mustawaId)
            ->orderBy('created_at')
            ->get();
        $mustawa = $mustawaId
            ? Mustawa::where('pondok_id', $pondokId)->findOrFail($mustawaId)
            : null;

        return Pdf::loadView('pendidikan.admin.absensi.pdf', [
            'logs' => $logs,
            'tanggal' => $validated['tanggal'],
            'mustawa' => $mustawa,
        ])->setPaper('a4', 'landscape')
            ->download('Rekap_Absensi_Diniyah_' . $validated['tanggal'] . '.pdf');
    }
}