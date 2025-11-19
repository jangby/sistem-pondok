<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;

class GuruAbsensiWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tipe; // 'sekolah' atau 'pelajaran'
    protected $detail; // Info tambahan (jam/mapel)

    public function __construct($tipe, $detail)
    {
        $this->tipe = $tipe;
        $this->detail = $detail;
    }

    public function via(mixed $notifiable): array
    {
        return ['waha'];
    }

    public function toWaha(mixed $notifiable): array
    {
        $text = "⚠️ *PERINGATAN ABSENSI*\n\n";
        $text .= "Halo *{$notifiable->name}*,\n";
        
        if ($this->tipe == 'sekolah') {
            $text .= "Anda belum melakukan **Absen Masuk Sekolah**.\n";
            $text .= "Batas waktu telat tinggal **5 menit lagi** ({$this->detail}).\n";
        } else {
            $text .= "Anda belum melakukan **Absen Mengajar** di kelas.\n";
            $text .= "Pelajaran: *{$this->detail}*\n";
            $text .= "Mohon segera masuk kelas dan lakukan absensi sebelum tercatat Terlambat/Alpa.\n";
        }

        $text .= "\n_Segera buka aplikasi dan lakukan absensi._";

        return ['text' => $text];
    }
}