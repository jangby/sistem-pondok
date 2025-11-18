<?php

namespace App\Services;

use App\Models\Dompet;
use App\Models\TransaksiDompet;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // <-- IMPORT CARBON


class DompetService
{
    /**
     * Fungsi inti untuk memodifikasi saldo dompet.
     *
     * @return bool|string True jika sukses, string error jika gagal.
     */
    public function createTransaksi(
        Dompet $dompet, 
        float $amount, 
        string $tipe, 
        string $keterangan, 
        ?User $pencatat = null, 
        ?Warung $warung = null
    ) {
        try {
            
            return DB::transaction(function () use ($dompet, $amount, $tipe, $keterangan, $pencatat, $warung) {

                // 1. Kunci dompet
                $dompet = Dompet::where('id', $dompet->id)->lockForUpdate()->first();

                // Cek Status Dompet
                if ($dompet->status == 'blocked') {
                    return "Dompet/kartu ini sedang diblokir.";
                }

                $saldoSebelum = $dompet->saldo;
                $nominal = 0;
                $saldoSetelah = 0;
                
                // 2. Tentukan Pemasukan / Pengeluaran
                if (in_array($tipe, ['topup_manual', 'topup_midtrans'])) {
                    $nominal = abs($amount); // Pastikan positif
                    $saldoSetelah = $saldoSebelum + $nominal;
                } 
                elseif (in_array($tipe, ['jajan', 'tarik_tunai'])) {
                    $nominal = abs($amount) * -1; // Pastikan negatif
                    $saldoSetelah = $saldoSebelum + $nominal;

                    // 3. Validasi Keamanan: Cek Saldo
                    if ($saldoSetelah < 0) {
                        return "Saldo tidak mencukupi (Sisa saldo: " . number_format($saldoSebelum, 0) . ").";
                    }

                    // --- INI LOGIKA BARU (CEK LIMIT HARIAN) ---
                    // Cek limit harian HANYA jika 'jajan'
                    if ($tipe == 'jajan' && $dompet->daily_spending_limit > 0) {
                        
                        // Hitung total jajan hari ini
                        $totalJajanHariIni = TransaksiDompet::where('dompet_id', $dompet->id)
                            ->where('tipe', 'jajan')
                            ->whereDate('created_at', Carbon::today())
                            ->sum(DB::raw('abs(nominal)')); // Ambil nilai absolut

                        $jajanBaru = abs($nominal);

                        if (($totalJajanHariIni + $jajanBaru) > $dompet->daily_spending_limit) {
                            return "Transaksi gagal: Melebihi limit jajan harian (Rp " . number_format($dompet->daily_spending_limit, 0) . ").";
                        }
                    }
                    // --- AKHIR LOGIKA BARU ---

                } else {
                    throw new \Exception('Tipe transaksi tidak valid.');
                }
                
                // 4. Update Saldo Dompet
                $dompet->saldo = $saldoSetelah;
                $dompet->save();

                // 5. Catat di Buku Kas Dompet
                TransaksiDompet::create([
                    'dompet_id' => $dompet->id,
                    'warung_id' => $warung ? $warung->id : null,
                    'user_id_pencatat' => $pencatat ? $pencatat->id : null,
                    'tipe' => $tipe,
                    'nominal' => $nominal,
                    'saldo_sebelum' => $saldoSebelum,
                    'saldo_setelah' => $saldoSetelah,
                    'keterangan' => $keterangan,
                ]);

                return true; // Sukses

            });

        } catch (\Exception $e) {
            Log::error("DompetService Error: " . $e->getMessage());
            return "Terjadi kesalahan server: " . $e->getMessage();
        }
    }
}