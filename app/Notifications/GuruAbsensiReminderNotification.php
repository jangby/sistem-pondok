<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;

class GuruAbsensiReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $waktuPulang;

    public function __construct($waktuPulang)
    {
        $this->waktuPulang = $waktuPulang;
    }

    public function via(mixed $notifiable): array
    {
        return ['waha'];
    }

    public function toWaha(mixed $notifiable): array
    {
        $text = "🔔 *PENGINGAT PULANG*\n\n";
        $text .= "Halo *{$notifiable->name}*,\n";
        $text .= "Sistem mendeteksi Anda sudah lebih dari **60 menit** melewati jadwal pulang ({$this->waktuPulang}) tetapi belum melakukan **Absen Pulang**.\n\n";
        $text .= "Jika Anda sudah meninggalkan sekolah, mohon segera lakukan absen pulang (atau abaikan jika masih lembur).\n";

        return ['text' => $text];
    }
}