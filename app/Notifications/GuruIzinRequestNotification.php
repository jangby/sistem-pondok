<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;
use App\Models\Sekolah\SekolahIzinGuru;

class GuruIzinRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $izin;
    public function __construct(SekolahIzinGuru $izin) { $this->izin = $izin; }
    public function via(mixed $notifiable): array { return ['waha']; }
    public function toWaha(mixed $notifiable): array
    {
        $namaGuru = $this->izin->guru->name;
        $tipe = $this->izin->tipe_izin;
        $tgl = $this->izin->tanggal_mulai->format('d/m/Y');
        
        $text = "🔔 Notifikasi Pengajuan Izin Baru\n\n";
        $text .= "Guru *{$namaGuru}* telah mengajukan *{$tipe}* untuk tanggal *{$tgl}*.\n\n";
        $text .= "Mohon segera ditinjau di panel Admin Sekolah Anda.\n";
        $text .= "Terima kasih.";
        return ['text' => $text];
    }
}