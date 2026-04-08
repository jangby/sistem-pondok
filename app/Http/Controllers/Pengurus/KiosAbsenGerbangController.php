<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalGerbang;
use App\Models\AbsensiGerbang;
use App\Models\Santri;
use Carbon\Carbon;

class KiosAbsenGerbangController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        $jadwalHariIni = JadwalGerbang::with('santri')->where('hari', $hariIni)->get();
        $absensiHariIni = AbsensiGerbang::where('tanggal', Carbon::today())->get()->keyBy('santri_id');

        return view('pengurus.absensi.gerbang.kios', compact('jadwalHariIni', 'absensiHariIni', 'hariIni'));
    }

    public function store(Request $request)
    {
        $request->validate(['santri_id' => 'required', 'pin' => 'required|digits:6', 'tipe_absen' => 'required|in:pagi,sore']);

        $santri = Santri::findOrFail($request->santri_id);

        if ($santri->pin_absen !== $request->pin) return back()->with('error', 'PIN salah! Silakan coba lagi.');

        $jamSekarang = Carbon::now()->format('H:i:s');
        $absen = AbsensiGerbang::firstOrNew(['santri_id' => $santri->id, 'tanggal' => Carbon::today()]);

        if ($request->tipe_absen == 'pagi') {
            if ($absen->absen_pagi) return back()->with('error', 'Sudah absen pagi.');
            $absen->absen_pagi = $jamSekarang;
        } else {
            if ($absen->absen_sore) return back()->with('error', 'Sudah absen sore.');
            $absen->absen_sore = $jamSekarang;
        }

        $absen->save();
        return back()->with('success', 'Absen ' . ucfirst($request->tipe_absen) . ' berhasil atas nama ' . $santri->full_name);
    }
}