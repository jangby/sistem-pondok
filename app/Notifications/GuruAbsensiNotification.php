<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel; //
use App\Models\Sekolah\AbsensiGuru;

class GuruAbsensiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $absensi;
    protected $pesan;

    /**
     * Buat instance notifikasi baru.
     *
     * @param \App\Models\Sekolah\AbsensiGuru $absensi
     * @param string $pesan (Cth: "Absen Masuk berhasil...")
     */
    public function __construct(AbsensiGuru $absensi, string $pesan)
    {
        $this->absensi = $absensi;
        $this->pesan = $pesan;
    }

    /**
     * Tentukan channel pengiriman (WAHA).
     */
    public function via(mixed $notifiable): array
    {
        return ['waha']; //
    }

    /**
     * Tentukan format pesan untuk WAHA.
     */
    public function toWaha(mixed $notifiable): array
    {
        $namaGuru = $notifiable->user->name; // Ambil nama dari relasi User
        $jam = \Carbon\Carbon::parse($this->absensi->jam_masuk)->format('H:i:s');
        if ($this->absensi->jam_pulang && !$this->absensi->jam_masuk) { // Jika ini absen pulang
             $jam = \Carbon\Carbon::parse($this->absensi->jam_pulang)->format('H:i:s');
        }
        
        $tanggal = \Carbon\Carbon::parse($this->absensi->tanggal)->locale('id_ID')->isoFormat('dddd, D MMMM Y');

        // Format pesan
        $text = "Assalamualaikum Wr. Wb. *{$namaGuru}*,\n\n";
        $text .= "Notifikasi Absensi Harian:\n";
        $text .= "✅ *{$this->pesan}*\n\n";
        $text .= "Detail:\n";
        $text .= "Tanggal: *{$tanggal}*\n";
        $text .= "Jam: *{$jam}*\n";
        $verifikasi = $this->absensi->verifikasi_masuk ?? $this->absensi->verifikasi_pulang;
        $text .= "Verifikasi: *{$verifikasi}*\n\n";
        $text .= "Terima kasih atas kedisiplinan Anda.\n";
        $text .= "_(Pesan ini dikirim otomatis oleh sistem)_";

        return [
            'text' => $text
        ];
    }
}