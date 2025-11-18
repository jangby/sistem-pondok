<?php

namespace App\Services;

use App\Models\Tagihan;
use App\Models\PembayaranTransaksi;
use App\Models\AlokasiPembayaran;
use App\Models\User;
use App\Notifications\PembayaranLunasNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * LOGIKA INTI: Mengalokasikan pembayaran ke item-item tagihan.
     * Ini sekarang bisa dipanggil dari mana saja.
     *
     * @param PembayaranTransaksi $transaksi Transaksi yang sudah terverifikasi.
     * @return bool
     */
    public function processPaymentAllocation(PembayaranTransaksi $transaksi)
    {
        // 1. Dapatkan tagihan terkait
        $tagihan = $transaksi->tagihan;
        if (!$tagihan) {
            Log::error("Alokasi Gagal: Transaksi {$transaksi->id} tidak punya Tagihan ID.");
            return false;
        }

        $sisaUangMasuk = $transaksi->total_bayar - $transaksi->biaya_admin;
        
        DB::beginTransaction();
        try {
            // 2. LOGIKA ALOKASI PRIORITAS
            $items = $tagihan->tagihanDetails()->orderBy('prioritas', 'asc')->get();

            foreach ($items as $item) {
                if ($sisaUangMasuk <= 0) break;
                
                $sisaTagihanItem = $item->sisa_tagihan_item;
                if ($sisaTagihanItem <= 0) continue;

                $uangUntukItemIni = min($sisaUangMasuk, $sisaTagihanItem);

                $item->sisa_tagihan_item -= $uangUntukItemIni;
                $sisaUangMasuk -= $uangUntukItemIni;
                
                if ($item->sisa_tagihan_item <= 0) {
                    $item->status_item = 'lunas';
                }
                $item->save();

                // 3. Catat di tabel alokasi
                AlokasiPembayaran::create([
                    'pembayaran_transaksi_id' => $transaksi->id,
                    'tagihan_detail_id' => $item->id,
                    'nominal_alokasi' => $uangUntukItemIni,
                ]);
            }

            // 4. Update Status Tagihan Utama
            $totalSisaTagihan = $tagihan->tagihanDetails()->sum('sisa_tagihan_item');
            if ($totalSisaTagihan <= 0.01) { // Pakai toleransi 0.01
                $tagihan->status = 'paid';
            } else {
                $tagihan->status = 'partial';
            }
            $tagihan->save();
            
            DB::commit();

            // 5. Kirim Notifikasi WA (setelah commit)
            try {
                $orangTua = $tagihan->santri->orangTua;
                $orangTua->notify(new PembayaranLunasNotification($transaksi, $tagihan));
            } catch (\Exception $e) {
                Log::error('Gagal kirim Notifikasi WA: ' . $e->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses alokasi pembayaran: ' . $e->getMessage(), [
                'transaksi_id' => $transaksi->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }


    /**
     * Memproses pembayaran manual oleh Admin (sekarang lebih ramping).
     */
    public function processManualPayment(Tagihan $tagihan, float $amount, User $admin, string $notes): bool
    {
        $sisaTagihan = $tagihan->tagihanDetails->sum('sisa_tagihan_item');
        if ($amount > $sisaTagihan) {
            $amount = $sisaTagihan; // Admin tidak boleh bayar lebih dari sisa tagihan
        }
        
        // 1. Buat catatan Transaksi Pembayaran (langsung verified)
        $transaksi = PembayaranTransaksi::create([
            'pondok_id' => $tagihan->pondok_id,
            'orang_tua_id' => $tagihan->santri->orang_tua_id,
            'tagihan_id' => $tagihan->id, // <-- Pastikan ini ada di $fillable
            'order_id_pondok' => 'MANUAL-' . $tagihan->pondok_id . '-' . time(),
            'total_bayar' => $amount,
            'biaya_admin' => 0,
            'tanggal_bayar' => now(),
            'metode_pembayaran' => 'cash',
            'payment_gateway' => 'manual_admin',
            'status_verifikasi' => 'verified',
            'verified_by_user_id' => $admin->id,
            'catatan_verifikasi' => $notes,
        ]);

        if (!$transaksi) {
            return false;
        }

        // 2. Panggil logika inti yang sudah dipisah
        return $this->processPaymentAllocation($transaksi);
    }

    /**
 * Membatalkan (Reversal) sebuah transaksi yang sudah terverifikasi.
 *
 * @param PembayaranTransaksi $transaksi Transaksi yang akan dibatalkan.
 * @param User $admin User admin yang melakukan pembatalan.
 * @return bool
 */
public function cancelPayment(PembayaranTransaksi $transaksi, User $admin): bool
{
    // 1. Validasi Aturan Bisnis
    if ($transaksi->status_verifikasi != 'verified') {
        Log::warning("Pembatalan Gagal: Transaksi {$transaksi->id} belum terverifikasi.");
        return false;
    }
    if ($transaksi->setoran_id != null) {
        Log::warning("Pembatalan Gagal: Transaksi {$transaksi->id} sudah masuk ke setoran.");
        return false;
    }

    DB::beginTransaction();
    try {
        // 2. Temukan semua alokasi dari transaksi ini
        $alokasis = $transaksi->alokasiDetails;
        $tagihan = $transaksi->tagihan;

        foreach ($alokasis as $alokasi) {
            // 3. Kembalikan uang (sisa tagihan) ke item-itemnya
            $itemTagihan = $alokasi->tagihanDetail;
            if ($itemTagihan) {
                $itemTagihan->sisa_tagihan_item += $alokasi->nominal_alokasi;
                $itemTagihan->status_item = 'pending'; // Set status item kembali ke pending
                $itemTagihan->save();
            }

            // 4. Hapus catatan alokasi
            $alokasi->delete();
        }

        // 5. Update status Tagihan utama
        if ($tagihan) {
            $totalSisaBaru = $tagihan->tagihanDetails()->sum('sisa_tagihan_item');
            if ($totalSisaBaru >= $tagihan->nominal_tagihan) {
                $tagihan->status = 'pending'; // Kembali ke pending
            } else {
                $tagihan->status = 'partial'; // Kembali ke partial
            }
            $tagihan->save();
        }

        // 6. Tandai Transaksi sebagai "DIBATALKAN"
        $transaksi->status_verifikasi = 'canceled';
        $transaksi->catatan_verifikasi = 'Dibatalkan oleh ' . $admin->name . ' pada ' . now();
        $transaksi->save();

        DB::commit();
        return true;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal membatalkan transaksi: ' . $e->getMessage());
        return false;
    }
}
}