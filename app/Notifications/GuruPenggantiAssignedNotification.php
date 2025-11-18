<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel;
use App\Models\Sekolah\AbsensiPelajaran;

class GuruPenggantiAssignedNotification extends Notification implements ShouldQueue
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
        // Load relasi yang dibutuhkan
        $this->absensiPelajaran->load('jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.kelas', 'jadwalPelajaran.guru');
        
        $jadwal = $this->absensiPelajaran->jadwalPelajaran;
        $namaGuruAsli = $jadwal->guru->name;
        $mapel = $jadwal->mataPelajaran->nama_mapel;
        $kelas = $jadwal->kelas->nama_kelas;
        $jam = $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai;
        $tanggal = \Carbon\Carbon::parse($this->absensiPelajaran->tanggal)->format('d/m/Y');

        $text = "🔔 *TUGAS GURU PENGGANTI*\n\n";
        $text .= "Yth. *{$notifiable->user->name}*,\n";
        $text .= "Anda ditugaskan untuk menggantikan kelas sementara.\n\n";
        $text .= "Detail Kelas:\n";
        $text .= "📅 Tanggal: {$tanggal}\n";
        $text .= "⏰ Jam: {$jam}\n";
        $text .= "📚 Mapel: {$mapel}\n";
        $text .= "🏫 Kelas: {$kelas}\n";
        $text .= "👤 Guru Asli: {$namaGuruAsli}\n\n";
        $text .= "Mohon segera menuju kelas tersebut. Jadwal ini sudah muncul di dashboard Anda.\n";
        $text .= "Terima kasih.";

        return ['text' => $text];
    }
}