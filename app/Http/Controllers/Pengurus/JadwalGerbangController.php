<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalGerbang;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalGerbangController extends Controller
{
    public function index()
    {
        $jadwals = JadwalGerbang::with('santri')->get();
        $santris = Santri::select('id', 'full_name', 'pin_absen')->orderBy('full_name', 'asc')->get(); 

        // Urutkan jadwal berdasarkan hari (Senin - Minggu)
        $urutanHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
        $jadwals = $jadwals->sortBy(function($jadwal) use ($urutanHari) {
            return $urutanHari[$jadwal->hari] ?? 8;
        })->values();

        return view('pengurus.absensi.gerbang.jadwal', compact('jadwals', 'santris'));
    }

    public function store(Request $request)
    {
        $request->validate(['santri_id' => 'required', 'hari' => 'required']);

        $cekJadwal = JadwalGerbang::where('santri_id', $request->santri_id)->where('hari', $request->hari)->first();

        if ($cekJadwal) return back()->with('error', 'Santri ini sudah dijadwalkan pada hari tersebut!');

        JadwalGerbang::create(['santri_id' => $request->santri_id, 'hari' => $request->hari]);
        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        JadwalGerbang::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }

    public function updatePin(Request $request)
    {
        $request->validate(['santri_id' => 'required', 'pin' => 'required|digits:6']);
        $santri = Santri::findOrFail($request->santri_id);
        $santri->pin_absen = $request->pin;
        $santri->save();

        return back()->with('success', 'PIN Absen untuk ' . $santri->full_name . ' berhasil disimpan!');
    }

    // Fungsi Baru: Cetak PDF Jadwal
    public function exportPdf()
    {
        $jadwals = JadwalGerbang::with('santri')->get();

        // Urutkan berdasarkan hari
        $urutanHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
        $jadwals = $jadwals->sortBy(function($jadwal) use ($urutanHari) {
            return $urutanHari[$jadwal->hari] ?? 8;
        })->values();

        // Kelompokkan data per Hari agar mudah ditampilkan di tabel
        $jadwalPerHari = $jadwals->groupBy('hari');

        $pdf = Pdf::loadView('pengurus.absensi.gerbang.pdf_jadwal', compact('jadwalPerHari'));
        
        // Ukuran A4 Portrait agar pas ditempel di dinding
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Jadwal_Piket_Gerbang.pdf');
    }
}