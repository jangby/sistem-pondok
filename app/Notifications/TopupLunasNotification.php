<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PembayaranTransaksi;
use App\Models\Dompet;

class TopupLunasNotification extends Notification implements ShouldQueue // Pastikan 'implements ShouldQueue'
{
    use Queueable;

    public $transaksi;
    public $dompet;

    /**
     * Create a new notification instance.
     */
    public function __construct(PembayaranTransaksi $transaksi, Dompet $dompet)
    {
        $this->transaksi = $transaksi;
        $this->dompet = $dompet;
    }

    /**
     * Tentukan channel (kita pakai 'waha' yang sudah kita daftarkan).
     */
    public function via(mixed $notifiable): array
    {
        return ['waha']; // Gunakan alias 'waha' dari AppServiceProvider
    }

    /**
     * Tentukan format pesan WA.
     */
    public function toWaha(mixed $notifiable): array
    {
        // Ambil nominal topup (Total Bayar - Biaya Admin)
        $nominalTopup = "Rp " . number_format($this->transaksi->total_bayar - $this->transaksi->biaya_admin, 0, ',', '.');
        $namaSantri = $this->dompet->santri->full_name;
        $saldoBaru = "Rp " . number_format($this->dompet->saldo, 0, ',', '.'); // dompet->saldo sudah di-update

        // Format pesan
        $text = "Assalamualaikum Wr. Wb.\n\n";
        $text .= "Top-up saldo uang jajan untuk Ananda *{$namaSantri}* telah berhasil.\n\n";
        $text .= "Detail Transaksi:\n";
        $text .= "Nominal Top-up: *{$nominalTopup}*\n";
        $text .= "Tanggal: " . $this->transaksi->tanggal_bayar->format('d M Y H:i') . "\n";
        $text .= "Saldo Dompet Saat Ini: *{$saldoBaru}*\n\n";
        $text .= "Terima kasih.\n";
        $text .= "_(Pesan ini dikirim otomatis oleh sistem)_";

        return [
            'text' => $text
        ];
    }
}