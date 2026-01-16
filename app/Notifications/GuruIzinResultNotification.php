<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;
use App\Models\Sekolah\SekolahIzinGuru;

class GuruIzinResultNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $izin;
    public function __construct(SekolahIzinGuru $izin) { $this->izin = $izin; }
    public function via($notifiable)
{
    // Pastikan return array berisi WahaChannel::class
    return ['database', WahaChannel::class]; 
}
    public function toWaha(mixed $notifiable): array
    {
        $status = $this->izin->status == 'approved' ? 'DISETUJUI' : 'DITOLAK';
        $icon = $status == 'DISETUJUI' ? '✅' : '❌';
        $tgl = $this->izin->tanggal_mulai->format('d/m/Y');

        $text = "{$icon} Hasil Pengajuan Izin/Sakit\n\n";
        $text .= "Pengajuan *{$this->izin->tipe_izin}* Anda untuk tanggal *{$tgl}* telah ditinjau.\n\n";
        $text .= "Status: *{$status}*\n";
        $text .= "Catatan Admin: {$this->izin->keterangan_admin}\n\n";
        $text .= "Terima kasih.";
        return ['text' => $text];
    }
}