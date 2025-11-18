<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Sekolah\AbsensiSiswaPelajaran; //
use Illuminate\Support\Facades\Log;

class ProcessSiswaAbsensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $absensiPelajaranId;
    protected $santriId;
    protected $waktuAbsen;

    /**
     * Buat instance Job baru.
     */
    public function __construct($absensiPelajaranId, $santriId, $waktuAbsen)
    {
        $this->absensiPelajaranId = $absensiPelajaranId;
        $this->santriId = $santriId;
        $this->waktuAbsen = $waktuAbsen;
    }

    /**
     * Eksekusi Job.
     */
    public function handle(): void
    {
        try {
            // Gunakan updateOrCreate (upsert) untuk mencatat absensi
            // Ini akan membuat data baru jika belum ada, atau meng-update jika sudah ada
            AbsensiSiswaPelajaran::updateOrCreate(
                [
                    'absensi_pelajaran_id' => $this->absensiPelajaranId,
                    'santri_id' => $this->santriId,
                ],
                [
                    'status' => 'hadir',
                    'jam_hadir' => $this->waktuAbsen,
                    'keterangan' => 'Hadir (via Scan)',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Job ProcessSiswaAbsensi Gagal: ' . $e->getMessage(), [
                'absensi_pelajaran_id' => $this->absensiPelajaranId,
                'santri_id' => $this->santriId,
            ]);
        }
    }
}