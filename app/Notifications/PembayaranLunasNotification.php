<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // <-- Implementasi antrian
use Illuminate\Notifications\Notification;
use App\Channels\WahaChannel; // <-- Channel kustom kita
use App\Models\PembayaranTransaksi;
use App\Models\Tagihan;

class PembayaranLunasNotification extends Notification implements ShouldQueue // <-- Terapkan ShouldQueue
{
    use Queueable;

    public $transaksi;
    public $tagihan;

    /**
     * Create a new notification instance.
     */
    public function __construct(PembayaranTransaksi $transaksi, Tagihan $tagihan)
    {
        $this->transaksi = $transaksi;
        $this->tagihan = $tagihan;
    }

    /**
     * Tentukan channel pengiriman (WAHA).
     */
    public function via(mixed $notifiable): array
{
    return ['waha']; // <-- Gunakan nama alias 'waha'
}

    /**
     * Tentukan format pesan untuk WAHA.
     */
    public function toWaha(mixed $notifiable): array
    {
        $namaSantri = $this->tagihan->santri->full_name;
        $jenisTagihan = $this->tagihan->jenisPembayaran->name;
        $nominalBayar = "Rp " . number_format($this->transaksi->total_bayar, 0, ',', '.');
        $statusTagihan = $this->tagihan->status == 'paid' ? 'LUNAS' : 'SEBAGIAN';

        // Format pesan
        $text = "Assalamualaikum Wr. Wb.\n\n";
        $text .= "Yth. Bpk/Ibu Wali dari Ananda *{$namaSantri}*,\n\n";
        $text .= "Kami informasikan bahwa pembayaran untuk tagihan *{$jenisTagihan}* telah kami terima.\n\n";
        $text .= "Detail Pembayaran:\n";
        $text .= "Jumlah Bayar: *{$nominalBayar}*\n";
        $text .= "Tanggal Bayar: " . $this->transaksi->tanggal_bayar->format('d M Y H:i') . "\n";
        $text .= "Status Tagihan: *{$statusTagihan}*\n\n";
        $text .= "Terima kasih atas pembayaran Anda.\n\n";
        $text .= "_(Pesan ini dikirim otomatis oleh sistem)_";

        return [
            'text' => $text
        ];
    }
}