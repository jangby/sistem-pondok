<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BukuTamu;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan package PDF

class BukuTamuController extends Controller
{
    public function index()
    {
        // Ambil data tamu khusus hari ini, urutkan dari yang terbaru
        $tamus = BukuTamu::whereDate('jam_masuk', Carbon::today())
                         ->orderBy('jam_masuk', 'desc')
                         ->get();

        return view('pengurus.absensi.gerbang.buku-tamu', compact('tamus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tamu' => 'required',
            'bertemu_dengan' => 'required',
            'keperluan' => 'required',
        ]);

        BukuTamu::create([
            'nama_tamu' => $request->nama_tamu,
            'instansi_asal' => $request->instansi_asal,
            'bertemu_dengan' => $request->bertemu_dengan,
            'keperluan' => $request->keperluan,
            'no_hp' => $request->no_hp,
            'jam_masuk' => now(),
        ]);

        return back()->with('success', 'Data tamu berhasil dicatat!');
    }

    public function checkout($id)
    {
        $tamu = BukuTamu::findOrFail($id);
        $tamu->jam_keluar = now();
        $tamu->save();

        return back()->with('success', 'Tamu atas nama ' . $tamu->nama_tamu . ' telah dicatat keluar.');
    }

    // Fungsi Baru: Cetak PDF Buku Tamu Bulanan
    public function exportPdf(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Ambil data tamu berdasarkan bulan & tahun yang dipilih
        $tamus = BukuTamu::whereMonth('jam_masuk', $bulan)
                         ->whereYear('jam_masuk', $tahun)
                         ->orderBy('jam_masuk', 'asc') // Urutkan dari tanggal terawal
                         ->get();

        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM Y');

        $pdf = Pdf::loadView('pengurus.absensi.gerbang.pdf_buku_tamu', compact('tamus', 'namaBulan'));
        
        // Ukuran A4 Landscape (Mendatar) karena kolomnya banyak
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Rekap_Buku_Tamu_' . $namaBulan . '.pdf');
    }
}