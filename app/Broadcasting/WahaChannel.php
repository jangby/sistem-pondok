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
        // 1. Dapatkan data pesan dari file Notifikasi
        $message = $notification->toWaha($notifiable);

        // 2. Dapatkan nomor WA (chatId) dari Model (OrangTua)
        $chatId = $notifiable->routeNotificationFor('waha', $notification);

        if (!$chatId) {
            Log::error('WAHA: Gagal kirim. No chatId for notifiable ID: ' . $notifiable->id);
            return;
        }

        // 3. Ambil konfigurasi dari config/waha.php
        $url = config('waha.url') . '/api/sendText';
        $apiKey = config('waha.api_key');
        $session = config('waha.session');

        // 4. Kirim data menggunakan Laravel HTTP Client
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'session' => $session,
            'chatId' => $chatId,
            'text' => $message['text'],
        ]);

        // 5. Catat log jika gagal
        if ($response->failed()) {
            Log::error('WAHA: Gagal mengirim pesan ke ' . $chatId, $response->json());
        }
    }
}