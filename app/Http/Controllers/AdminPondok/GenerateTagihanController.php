<?php

namespace App\Http\Controllers\AdminPondok;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisPembayaran;
use App\Models\Santri;
use App\Jobs\ProcessTagihanGeneration; // <-- IMPORT JOB
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessFutureTagihan; // <-- IMPORT (Akan kita buat)
use Carbon\Carbon; // <-- IMPORT
use App\Jobs\ProcessMassiveTagihan;

class GenerateTagihanController extends Controller
{
    /**
     * Tampilkan form generator tagihan
     */
    public function create()
    {
        // Trait otomatis memfilter
        $jenisPembayarans = JenisPembayaran::orderBy('name')->get();

        if ($jenisPembayarans->isEmpty()) {
            return redirect()->route('adminpondok.jenis-pembayarans.index')
                             ->with('error', 'Gagal! Silakan tambahkan Jenis Pembayaran terlebih dahulu.');
        }

        return view('adminpondok.tagihan.create', compact('jenisPembayarans'));
    }

    /**
     * Simpan (Mulai proses) generator tagihan
     */
    public function store(Request $request)
    {
        $pondokId = Auth::user()->pondokStaff->pondok_id;
        
        $validated = $request->validate([
            // 'jenis_pembayaran_id' HILANG (sudah benar)
            'due_date' => 'required|date',
            'periode_bulan' => 'required|numeric|min:1|max:12',
            'periode_tahun' => 'required|numeric|min:2020|max:2050',
        ]);
        
        // Ambil SEMUA santri aktif
        $santriCollection = Santri::withoutGlobalScopes()
                                ->where('pondok_id', $pondokId)
                                ->where('status', 'active')
                                ->get();
        
        $dataInput = [
            'due_date' => $validated['due_date'],
            'periode_bulan' => $validated['periode_bulan'],
            'periode_tahun' => $validated['periode_tahun'],
        ];

        // Jalankan Job (Kita perlu job baru yang lebih pintar)
        ProcessMassiveTagihan::dispatch($pondokId, $santriCollection, $dataInput); // Job baru

        return redirect()->route('adminpondok.tagihan.index')
                         ->with('success', 'Proses generate tagihan sedang berjalan...');
    }

    public function storeFuture(Request $request, Santri $santri)
{
    // Trait di model Santri sudah mengamankan
    $validated = $request->validate([
        'jenis_pembayaran_id' => 'required|exists:jenis_pembayarans,id',
        'due_date' => 'required|date',
        // Opsi Bulanan
        'jumlah_bulan' => 'nullable|numeric|min:1|max:24',
        'mulai_bulan' => 'nullable|numeric|min:1|max:12',
        'mulai_tahun_bulan' => 'nullable|numeric|min:2024',
        // Opsi Tahunan
        'mulai_tahun_tahunan' => 'nullable|numeric|min:2024',
    ]);

    $jenisPembayaran = \App\Models\JenisPembayaran::find($validated['jenis_pembayaran_id']);

    $jobData = [
        'santri' => $santri,
        'jenisPembayaran' => $jenisPembayaran,
        'due_date' => $validated['due_date'],
    ];

    if ($jenisPembayaran->tipe == 'bulanan') {
        $jobData['jumlah_periode'] = $validated['jumlah_bulan'] ?? 1;
        $jobData['mulai_bulan'] = $validated['mulai_bulan'];
        $jobData['mulai_tahun'] = $validated['mulai_tahun_bulan'];
    } else { // Tahunan / Semesteran
        $jobData['jumlah_periode'] = 1; // Hanya 1 tahun/semester
        $jobData['mulai_bulan'] = null;
        $jobData['mulai_tahun'] = $validated['mulai_tahun_tahunan'];
    }

    // Jalankan Job di Latar Belakang
    ProcessFutureTagihan::dispatch($jobData);

    // Redirect kembali ke halaman detail santri
    return redirect()->route('adminpondok.santris.show', $santri->id)
                     ->with('success', 'Proses generate tagihan masa depan sedang berjalan di latar belakang.');
}
}