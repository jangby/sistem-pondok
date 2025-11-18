<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;
use App\Models\Sekolah\AbsensiPelajaran;

class GuruAbsenMengajarNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $absensiPelajaran;

    public function __construct(AbsensiPelajaran $absensiPelajaran)
    {
        $this->absensiPelajaran = $absensiPelajaran;
    }

    public function via(mixed $notifiable): array
    {
        return ['waha']; //
    }

    public function toWaha(mixed $notifiable): array
    {
        $jadwal = $this->absensiPelajaran->jadwalPelajaran; //
        
        $namaGuru = $notifiable->user->name;
        $jam = \Carbon\Carbon::parse($this->absensiPelajaran->jam_guru_masuk_kelas)->format('H:i:s');
        $mapel = $jadwal->mataPelajaran->nama_mapel; //
        $kelas = $jadwal->kelas->nama_kelas; //

        // Format pesan
        $text = "Notifikasi Absensi Mengajar:\n\n";
        $text .= "✅ *{$namaGuru}* berhasil melakukan absensi mengajar.\n\n";
        $text .= "Detail:\n";
        $text .= "Jam: *{$jam}*\n";
        $text .= "Mata Pelajaran: *{$mapel}*\n";
        $text .= "Kelas: *{$kelas}*\n\n";
        $text .= "Silakan lanjutkan dengan absensi siswa.";
        $text .= "_(Pesan ini dikirim otomatis oleh sistem)_";

        return [
            'text' => $text
        ];
    }
}