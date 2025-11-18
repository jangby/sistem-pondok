<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KesehatanSantri;
use App\Models\Perizinan;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->pondokStaff) abort(403);
        $pondokId = $user->pondokStaff->pondok_id;

        // PERBAIKAN: Tambahkan where('status', '!=', 'sembuh')
        $sakitHariIni = KesehatanSantri::where('pondok_id', $pondokId)
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'sembuh') // <-- KUNCI PERBAIKANNYA
            ->count();

        // Izin yang AKTIF (Disetujui & Belum Lewat Tanggal)
        $izinKeluarAktif = Perizinan::where('pondok_id', $pondokId)
            ->where('status', 'disetujui')
            ->where('tgl_mulai', '<=', now())
            ->where('tgl_selesai_rencana', '>=', now())
            ->count();

        $hadirHariIni = Absensi::where('pondok_id', $pondokId)
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'hadir')
            ->count();

        return view('pengurus.dashboard', compact('sakitHariIni', 'izinKeluarAktif', 'hadirHariIni'));
    }
}