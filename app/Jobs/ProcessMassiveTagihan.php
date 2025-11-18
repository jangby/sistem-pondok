<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Models\JenisPembayaran;
use App\Models\Santri;
use App\Services\TagihanService; // "OTAK" KITA
use Illuminate\Support\Facades\Log;

class ProcessMassiveTagihan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pondokId;
    protected $santriCollection;
    protected $dataInput; // Berisi bulan, tahun, due_date

    public function __construct(int $pondokId, Collection $santriCollection, array $dataInput)
    {
        $this->pondokId = $pondokId;
        $this->santriCollection = $santriCollection;
        $this->dataInput = $dataInput;
    }

    /**
     * Execute the job.
     */
    public function handle(TagihanService $tagihanService): void
    {
        $bulan = $this->dataInput['periode_bulan'];
        $tahun = $this->dataInput['periode_tahun'];

        // Loop 1: Per Santri
        foreach ($this->santriCollection as $santri) {
            
            // 1. Cek apakah santri punya kelas_id
            if (!$santri->kelas_id) {
                Log::info("Skipping Santri {$santri->id}: Tidak punya kelas_id.");
                continue;
            }
            
            // --- INI PERBAIKANNYA ---
            // 2. "Bangunkan" model santri dan paksa load relasi yang kita butuh
            // Ini akan mengambil data baru dari DB
            $santri->load('kelas.jenisPembayarans'); 

            // 3. Cek lagi, apakah 'kelas' nya benar-benar ada (bukan null)
            if (!$santri->kelas) {
                Log::warning("Skipping Santri {$santri->id}: Data kelas tidak ditemukan (ID: {$santri->kelas_id}). Mungkin sudah terhapus?");
                continue;
            }
            // -------------------------
            
            // 4. Ambil SEMUA jenis pembayaran (sekarang sudah aman)
            $jenisPembayarans = $santri->kelas->jenisPembayarans;

            // Loop 2: Per Jenis Pembayaran
            foreach ($jenisPembayarans as $jenisPembayaran) {
                
                // Cek Tipe: Apakah tagihan ini sesuai periode?
                $tipe = $jenisPembayaran->tipe;
                
                if ($tipe == 'bulanan') {
                    // (Lolos, akan diproses)
                } 
                elseif ($tipe == 'semesteran' && ($bulan == 1 || $bulan == 7)) { // Cth: Jan & Juli
                    // (Lolos, akan diproses)
                }
                elseif ($tipe == 'tahunan' && $bulan == 7) { // Cth: Hanya di bulan Juli
                    // (Lolos, akan diproses)
                }
                else {
                    // Jika tidak cocok, lanjut ke jenis pembayaran berikutnya
                    continue; 
                }

                // Jika lolos, panggil "Otak" Generator
                try {
                    $tagihanService->generateTagihan(
                        $santri,
                        $jenisPembayaran,
                        $this->dataInput // dataInput sudah berisi bulan, tahun, due_date
                    );
                } catch (\Exception $e) {
                    Log::error("Job Massal Gagal Memanggil Service: Santri ID {$santri->id}. Error: " . $e->getMessage());
                    continue;
                }
            }
        }
    }
}