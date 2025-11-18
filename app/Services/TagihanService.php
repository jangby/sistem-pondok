<?php

namespace App\Services;

use App\Models\Santri;
use App\Models\JenisPembayaran;
use App\Models\Keringanan;
use App\Models\Tagihan;
use App\Models\TagihanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TagihanService
{
    /**
     * "Otak" Generator Tagihan.
     * Fungsi ini akan membuat SATU tagihan untuk SATU santri
     * untuk SATU periode, dengan semua logika cek duplikat & keringanan.
     *
     * @param Santri $santri Santri yang akan ditagih
     * @param JenisPembayaran $jenisPembayaran Jenis tagihan (cth: SPP)
     * @param array $dataInput Data periode (cth: ['due_date' => '2025-12-10', 'periode_bulan' => 12, 'periode_tahun' => 2025])
     * @return bool True jika sukses atau duplikat, False jika error.
     */
    public function generateTagihan(Santri $santri, JenisPembayaran $jenisPembayaran, array $dataInput): bool
    {
        // =================================================================
        // 1. [PERLINDUNGAN DUPLIKAT]
        // =================================================================
        $query = Tagihan::where('santri_id', $santri->id)
                        ->where('jenis_pembayaran_id', $jenisPembayaran->id);

        $tipe = $jenisPembayaran->tipe;
        $bulan = $dataInput['periode_bulan'] ?? null;
        $tahun = $dataInput['periode_tahun'] ?? null;

        if ($tipe == 'bulanan') {
            if ($bulan && $tahun) {
                $query->where('periode_bulan', $bulan)->where('periode_tahun', $tahun);
            } else {
                Log::warning("GenerateTagihan Service SKIPPED (Invalid Data): Missing period for monthly payment. Santri ID {$santri->id}, Jenis ID {$jenisPembayaran->id}");
                return false; // Error data
            }
        } elseif ($tipe == 'tahunan' || $tipe == 'semesteran') {
            if ($tahun) {
                 $query->where('periode_tahun', $tahun);
            } else {
                Log::warning("GenerateTagihan Service SKIPPED (Invalid Data): Missing year for periodic payment. Santri ID {$santri->id}, Jenis ID {$jenisPembayaran->id}");
                return false; // Error data
            }
        }
        
        if ($query->exists()) {
            Log::info("GenerateTagihan Service SKIPPED (Duplicate): Santri ID {$santri->id} already has tagihan for Jenis ID {$jenisPembayaran->id} for this period.");
            return true; // Dianggap "sukses" karena tagihan sudah ada
        }

        // =================================================================
        // 2. Ambil Template Item
        // =================================================================
        $templateItems = $jenisPembayaran->items()->orderBy('prioritas', 'asc')->get();
        if ($templateItems->isEmpty()) {
            Log::warning("GenerateTagihan Service GAGAL: Jenis Pembayaran ID {$jenisPembayaran->id} tidak memiliki item rincian.");
            return false; // Error
        }
        $totalTagihanAsli = $templateItems->sum('nominal');
        
        // =================================================================
        // 3. Mulai Transaksi Database
        // =================================================================
        DB::beginTransaction();
        try {
            // 4. [LOGIKA KERINGANAN]
            $keringanan = Keringanan::where('santri_id', $santri->id)
                            ->where('jenis_pembayaran_id', $jenisPembayaran->id)
                            ->where('berlaku_mulai', '<=', $dataInput['due_date']) // Cek vs Tanggal Terbit
                            ->where(function ($q) use ($dataInput) {
                                $q->where('berlaku_sampai', '>=', $dataInput['due_date'])
                                  ->orWhereNull('berlaku_sampai');
                            })
                            ->first();

            $totalKeringanan = 0;
            $itemsUntukDisimpan = $templateItems->map(fn ($item) => $item->toArray());

            if ($keringanan) {
                if ($keringanan->tipe_keringanan == 'nominal_tetap') {
                    $totalKeringanan = $keringanan->nilai;
                } else { // Persentase
                    $totalKeringanan = $totalTagihanAsli * ($keringanan->nilai / 100);
                }
                
                // (Logika potong dari prioritas terendah)
                $sisaDiskon = $totalKeringanan;
                $itemsUntukDisimpan = $itemsUntukDisimpan->sortByDesc('prioritas')->map(function ($item) use (&$sisaDiskon) {
                    if ($sisaDiskon <= 0) return $item;
                    $nominalItem = $item['nominal'];
                    if ($sisaDiskon >= $nominalItem) {
                        $sisaDiskon -= $nominalItem;
                        $item['nominal'] = 0; 
                    } else {
                        $item['nominal'] = $nominalItem - $sisaDiskon;
                        $sisaDiskon = 0;
                    }
                    return $item;
                });
            }
            
            // 5. [BUAT TAGIHAN UTAMA]
            
            // --- INI PERBAIKAN PENTING UNTUK INVOICE UNIK ---
            // Kita buat nomor invoice unik dengan menambahkan periode
            $uniquePeriod = ($dataInput['periode_bulan'] ?? 'NP') . ($dataInput['periode_tahun'] ?? date('Y'));
            
            // Kita tambahkan microtime() untuk jaminan unik 100%
            // time() hanya unik per detik, microtime(true) unik per mikrodetik
            $uniqueTime = str_replace('.', '', (string) microtime(true)); 
            
            $invoiceNumber = 'INV/' . $santri->pondok_id . '/' . $santri->id . '/' . $uniqueTime . '-' . $uniquePeriod;
            // Hasil baru: INV/1/1/17629059174567-12026 (lebih aman)
            
            $tagihan = Tagihan::create([
                'pondok_id' => $santri->pondok_id, // Ambil dari santri
                'santri_id' => $santri->id,
                'jenis_pembayaran_id' => $jenisPembayaran->id,
                'invoice_number' => $invoiceNumber, // <-- Gunakan variabel baru
                'nominal_asli' => $totalTagihanAsli,
                'nominal_keringanan' => $totalKeringanan,
                'nominal_tagihan' => $totalTagihanAsli - $totalKeringanan,
                'periode_bulan' => $dataInput['periode_bulan'] ?? null,
                'periode_tahun' => $dataInput['periode_tahun'] ?? null,
                'due_date' => $dataInput['due_date'],
                'status' => 'pending',
                'keterangan' => $keringanan ? ($keringanan->keterangan ?? 'Mendapat keringanan') : null,
            ]);

            // 6. [BUAT TAGIHAN DETAIL]
            $detailItemsData = $itemsUntukDisimpan->sortBy('prioritas')->map(function ($item) use ($tagihan) {
                return [
                    'tagihan_id' => $tagihan->id,
                    'nama_item' => $item['nama_item'],
                    'prioritas' => $item['prioritas'],
                    'nominal_item' => $item['nominal'],
                    'sisa_tagihan_item' => $item['nominal'],
                    'status_item' => $item['nominal'] == 0 ? 'lunas' : 'pending',
                    'created_at' => now(), 'updated_at' => now(),
                ];
            });
            TagihanDetail::insert($detailItemsData->toArray());

            DB::commit();
            return true; // Sukses

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("GenerateTagihan Service GAGAL: Santri ID {$santri->id}. Error: " . $e->getMessage());
            return false; // Error
        }
    }
}