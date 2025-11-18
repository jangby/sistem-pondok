<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Models\JenisPembayaran;
use App\Models\Keringanan;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TagihanService;

class ProcessTagihanGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pondokId;
    protected $jenisPembayaran;
    protected $santriCollection;
    protected $dataInput;

    /**
     * Create a new job instance.
     */
    public function __construct(int $pondokId, JenisPembayaran $jenisPembayaran, Collection $santriCollection, array $dataInput)
    {
        $this->pondokId = $pondokId;
        $this->jenisPembayaran = $jenisPembayaran;
        $this->santriCollection = $santriCollection;
        $this->dataInput = $dataInput;
    }

    /**
     * Execute the job.
     */
    public function handle(TagihanService $tagihanService): void
    {
        // 1. Siapkan data input (hanya 1 kali)
        $dataInput = [
            'due_date' => $this->dataInput['due_date'],
            'periode_bulan' => $this->dataInput['periode_bulan'] ?? null,
            'periode_tahun' => $this->dataInput['periode_tahun'] ?? ($this->jenisPembayaran->tipe == 'bulanan' ? date('Y') : null),
        ];

        // 2. Loop setiap santri
        foreach ($this->santriCollection as $santri) {
            
            // 3. Panggil "Otak" Generator yang sudah terpusat
            try {
                $tagihanService->generateTagihan(
                    $santri,
                    $this->jenisPembayaran,
                    $dataInput
                );
            } catch (\Exception $e) {
                Log::error("Job Massal Gagal Memanggil Service: Santri ID {$santri->id}. Error: " . $e->getMessage());
                continue;
            }
        }
    }
}