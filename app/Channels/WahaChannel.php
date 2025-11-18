<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WahaChannel
{
    /**
     * Kirim notifikasi yang diberikan.
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        // 1. Dapatkan data pesan dari file Notifikasi (cth: PembayaranLunasNotification)
        $message = $notification->toWaha($notifiable);
        
        // 2. Dapatkan nomor WA (chatId) dari Model (OrangTua)
        // Ini akan memanggil fungsi routeNotificationForWaha() di Model OrangTua
        $chatId = $notifiable->routeNotificationFor('waha', $notification);

        if (!$chatId) {
            Log::error('WAHA Gagal: Tidak ada nomor WA (chatId) untuk notifiable ID: ' . $notifiable->id);
            return;
        }

        // 3. Ambil konfigurasi dari config/waha.php (yang kita buat dari .env)
        $url = config('waha.url') . '/api/sendText';
        $apiKey = config('waha.api_key');
        $session = config('waha.session');

        if (!$url || !$apiKey) {
            Log::error('WAHA Gagal: URL atau API Key belum di-setting di .env atau config/waha.php');
            return;
        }

        // 4. Kirim data menggunakan Laravel HTTP Client (cURL)
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ])
        // Timeouts untuk mencegah antrian macet jika WAHA server lambat
        ->timeout(10) // Total timeout
        ->connectTimeout(5) // Timeout koneksi
        ->post($url, [
            'session' => $session,
            'chatId' => $chatId,
            'text' => $message['text'],
        ]);

        // 5. Catat log jika gagal
        if ($response->failed()) {
            Log::error('WAHA Gagal: Gagal mengirim pesan ke ' . $chatId, [
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ]);
        } else {
            Log::info('WAHA Sukses: Pesan terkirim ke ' . $chatId);
        }
    }
}