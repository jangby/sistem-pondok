<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;

class GuruNilaiReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $kegiatan;
    protected $completion;

    public function __construct($kegiatan, $completion) { 
        $this->kegiatan = $kegiatan; 
        $this->completion = $completion; 
    }
    public function via(mixed $notifiable): array { return ['waha']; }
    public function toWaha(mixed $notifiable): array
    {
        $text = "🔔 *PERINGATAN INPUT NILAI*\n\n";
        $text .= "Yth. Guru *{$notifiable->name}*,\n";
        $text .= "Batas akhir kegiatan *{$this->kegiatan->nama_kegiatan}* adalah hari ini.\n\n";
        $text .= "Progress input nilai Anda masih *{$this->completion}%*.\n";
        $text .= "Mohon segera lengkapi input nilai sebelum sistem terkunci. Terima kasih.";
        return ['text' => $text];
    }
}