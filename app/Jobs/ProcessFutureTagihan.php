<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TagihanService; // <-- "OTAK" KITA
use App\Models\Santri;
use App\Models\JenisPembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessFutureTagihan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $jobData)
    {
        $this->jobData = $jobData;
    }

    /**
     * Execute the job.
     */
    public function handle(TagihanService $tagihanService): void
    {
        $santri = $this->jobData['santri'];
        $jenisPembayaran = $this->jobData['jenisPembayaran'];
        $jumlahPeriode = $this->jobData['jumlah_periode'];
        
        // Tentukan tanggal awal
        $tanggalMulai = Carbon::create(
            $this->jobData['mulai_tahun'],
            $this->jobData['mulai_bulan'] ?? 1, // '1' untuk Januari jika tahunan
            1
        );
        $tanggalJatuhTempoAwal = Carbon::parse($this->jobData['due_date']);
        
        Log::info("Memulai Job Generate Future Tagihan untuk Santri: {$santri->full_name}. Periode: {$jumlahPeriode}x");

        for ($i = 0; $i < $jumlahPeriode; $i++) {
            
            $dataInput = []; // Reset $dataInput

            if ($jenisPembayaran->tipe == 'bulanan') {
                // --- INI BLOK UNTUK BULANAN (YANG ANDA UJI) ---
                $tanggalPeriodeSaatIni = $tanggalMulai->copy()->addMonths($i);
                $dataInput['periode_bulan'] = $tanggalPeriodeSaatIni->month;
                $dataInput['periode_tahun'] = $tanggalPeriodeSaatIni->year;
                
                // Jatuh tempo di-increment per BULAN
                $dataInput['due_date'] = $tanggalJatuhTempoAwal->copy()->addMonths($i)->format('Y-m-d'); 

            } else { 
                // --- INI BLOK UNTUK TAHUNAN / SEMESTERAN ---
                $tanggalPeriodeSaatIni = $tanggalMulai->copy()->addYears($i);
                $dataInput['periode_bulan'] = null;
                $dataInput['periode_tahun'] = $tanggalPeriodeSaatIni->year;

                // Jatuh tempo di-increment per TAHUN
                // (Kita asumsikan semesteran juga increment tahunan, 
                // jika tidak, logikanya perlu disesuaikan lagi)
                $dataInput['due_date'] = $tanggalJatuhTempoAwal->copy()->addYears($i)->format('Y-m-d');
            }

            // HAPUS BARIS LAMA YANG SALAH DI SINI:
            // $dataInput['due_date'] = $tanggalJatuhTempo->copy()->addMonths($i)->format('Y-m-d'); 

            // Panggil "Otak" Generator
            try {
                $tagihanService->generateTagihan(
                    $santri,
                    $jenisPembayaran,
                    $dataInput
                );
            } catch (\Exception $e) {
                Log::error("Job Gagal saat memanggil Service: Santri ID {$santri->id}. Error: " . $e->getMessage());
                continue; // Lanjut ke iterasi loop berikutnya
            }
        }
        
        Log::info("Selesai Job Generate Future Tagihan untuk Santri: {$santri->full_name}");
    }
}