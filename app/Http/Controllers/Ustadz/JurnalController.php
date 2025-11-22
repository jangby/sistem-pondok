<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JurnalPendidikan;
use App\Models\Mustawa;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    /**
     * Halaman Utama Jurnal (Dashboard Kecil)
     */
    public function index()
    {
        // Ambil ID Ustadz yang sedang login
        $ustadzId = Auth::user()->ustadz->id ?? 0;

        // Ambil riwayat setoran hari ini
        $hariIni = JurnalPendidikan::where('ustadz_id', $ustadzId)
            ->whereDate('tanggal', Carbon::today())
            ->with('santri', 'santri.mustawa')
            ->latest()
            ->get();

        // Hitung total hari ini
        $totalHariIni = $hariIni->count();

        return view('pendidikan.ustadz.jurnal.index', compact('hariIni', 'totalHariIni'));
    }

    /**
     * Form Input Hafalan Baru
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $pondokId = $user->pondok_id;

        // 1. Ambil Daftar Kelas (Mustawa) untuk Dropdown
        $mustawas = Mustawa::where('pondok_id', $pondokId)
            ->where('is_active', true)
            ->orderBy('tingkat')
            ->get();

        // 2. Logika Filter Santri berdasarkan Kelas yang dipilih
        $santris = collect(); // Kosong defaultnya
        $selectedMustawaId = $request->query('mustawa_id');

        if ($selectedMustawaId) {
            $santris = Santri::where('mustawa_id', $selectedMustawaId)
                ->where('status', 'active')
                ->orderBy('full_name')
                ->get();
        }

        return view('pendidikan.ustadz.jurnal.create', compact('mustawas', 'santris', 'selectedMustawaId'));
    }

    /**
     * Proses Simpan Data
     */
    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kategori_hafalan' => 'required', // quran, hadits, dll
            'jenis_setoran' => 'required', // ziyadah/murojaah
            'materi' => 'required|string', // Nama Surat/Kitab
            'predikat' => 'required', // A, B, C
            'tanggal' => 'required|date',
        ]);

        // Simpan ke Database
        JurnalPendidikan::create([
            'santri_id' => $request->santri_id,
            'ustadz_id' => Auth::user()->ustadz->id,
            'jenis' => 'hafalan', // Hardcode karena ini modul khusus hafalan
            'kategori_hafalan' => $request->kategori_hafalan,
            'jenis_setoran' => $request->jenis_setoran,
            'materi' => $request->materi, // Misal: Al-Mulk
            'start_at' => $request->start_at, // Ayat 1
            'end_at' => $request->end_at,     // Ayat 5
            'predikat' => $request->predikat,
            'catatan' => $request->catatan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('ustadz.jurnal.index')->with('success', 'Setoran berhasil dicatat!');
    }

    /**
     * Lihat Detail/Riwayat (Opsional)
     */
    public function show($id)
    {
        $jurnal = JurnalPendidikan::with('santri')->findOrFail($id);
        return view('pendidikan.ustadz.jurnal.show', compact('jurnal'));
    }
}