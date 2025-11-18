<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gate\GateLog;
use App\Models\Gate\GateSetting;
use App\Models\Perizinan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CheckGateOvertime extends Command
{
    protected $signature = 'gate:check';
    protected $description = 'Cek santri yang keluar melebihi batas waktu';

    public function handle()
    {
        $this->info('Memulai pengecekan gerbang: ' . now());

        // 1. Ambil semua setting pondok yang aktif
        $settings = GateSetting::where('auto_notify', true)->get();

        foreach ($settings as $setting) {
            $limitMenit = $setting->max_minutes_out;
            $nomorSatpam = $setting->satpam_wa_number;
            $pondokId = $setting->pondok_id;

            if (!$nomorSatpam) {
                $this->warn("Pondok ID {$pondokId} tidak memiliki nomor WA Satpam.");
                continue;
            }

            // 2. Cari Pelanggar
            // Syarat: Belum kembali (in_time NULL), Belum dinotif, dan Waktu keluar sudah lewat batas
            $batasWaktu = now()->subMinutes($limitMenit);
            
            $violators = GateLog::where('pondok_id', $pondokId)
                ->whereNull('in_time')
                ->where('notified', false) // Hanya yang belum dinotif
                ->where('out_time', '<', $batasWaktu)
                ->with('santri')
                ->get();

            if ($violators->isNotEmpty()) {
                $this->info("Pondok ID {$pondokId}: Ditemukan {$violators->count()} santri terlambat.");
            }

            foreach ($violators as $log) {
                
                // 3. Cek Apakah Punya Izin Resmi?
                $isResmi = Perizinan::where('santri_id', $log->santri_id)
                    ->where('status', 'disetujui')
                    ->where('tgl_mulai', '<=', now())
                    ->where('tgl_selesai_rencana', '>=', now())
                    ->exists();

                if ($isResmi) {
                    // Jika punya izin resmi, tandai sudah dicek tapi jangan kirim WA
                    $log->update(['notified' => true]); 
                    $this->info("Santri {$log->santri->full_name} memiliki izin resmi. Skip.");
                    continue; 
                }

                // 4. Kirim WA ke Satpam
                $durasi = $log->out_time->diffForHumans(now(), true);
                $jamKeluar = $log->out_time->format('H:i');

                $pesan = "*ALARM GERBANG OTOMATIS* 🚨\n\n";
                $pesan .= "Nama: *{$log->santri->full_name}*\n";
                $pesan .= "Keluar: {$jamKeluar}\n";
                $pesan .= "Durasi: {$durasi} (Batas: {$limitMenit} menit)\n\n";
                $pesan .= "Status: *TANPA IZIN / TERLAMBAT*\n";
                $pesan .= "Mohon segera dicari/diamankan.";

                $statusKirim = $this->sendWa($nomorSatpam, $pesan);
                
                if ($statusKirim) {
                    // 5. Update Status Database HANYA jika WA berhasil terkirim
                    $log->update([
                        'notified' => true, 
                        'is_late' => true
                    ]);
                    $this->info("Notifikasi SUKSES dikirim untuk: {$log->santri->full_name}");
                } else {
                    $this->error("GAGAL mengirim notifikasi untuk: {$log->santri->full_name}");
                }
            }
        }
        
        $this->info('Pengecekan selesai.');
    }

    private function sendWa($to, $message) {
        // Format Nomor (Hapus 0 depan, ganti 62)
        $to = preg_replace('/^0/', '62', $to);
        $to = preg_replace('/[+\-\s]/', '', $to);
        
        try {
            // Ambil Config dari .env / file config
            $baseUrl = config('waha.url', 'http://localhost:3000');
            $apiKey = config('waha.api_key'); // Pastikan ini ada di config/waha.php
            $sessionName = config('waha.session_name', 'default');

            $url = $baseUrl . '/api/sendText'; 
            
            // PERBAIKAN: Tambahkan Header X-Api-Key
            $response = Http::withHeaders([
                'X-Api-Key' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post($url, [
                'chatId' => $to . '@c.us',
                'text' => $message,
                'session' => $sessionName
            ]);
            
            if ($response->failed()) {
                Log::error("Gagal kirim WA: " . $response->body());
                return false;
            }
            
            return true;

        } catch (\Exception $e) {
            Log::error("Error koneksi WA: " . $e->getMessage());
            return false;
        }
    }
}