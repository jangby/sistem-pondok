<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTransaksi extends Model
{
    use HasFactory, BelongsToPondok;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'pondok_id',
        'orang_tua_id',
        'tagihan_id', // Ini yang kita tambahkan sebelumnya
        'midtrans_order_id',
        'order_id_pondok',
        'dompet_id',
        'total_bayar',
        'biaya_admin',
        'tanggal_bayar',
        'metode_pembayaran',
        'payment_gateway',
        'bukti_bayar_url', // Untuk simpan VA/QR URL
        'status_verifikasi',
        'verified_by_user_id',
        'catatan_verifikasi',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    /**
     * Relasi ke Orang Tua yang membayar.
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class);
    }

    /**
     * Relasi ke Admin/User yang memverifikasi.
     */
    public function adminVerifier()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Relasi ke detail alokasi pembayarannya.
     */
    public function alokasiDetails()
    {
        return $this->hasMany(AlokasiPembayaran::class);
    }

    /**
     * INI FUNGSI YANG HILANG (PENYEBAB ERROR 500)
     * Relasi ke Tagihan induk yang dibayar oleh transaksi ini.
     */
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    /**
 * Relasi ke Setoran (jika sudah disetor).
 */
public function setoran()
{
    return $this->belongsTo(Setoran::class);
}
public function payout()
{
    return $this->belongsTo(Payout::class);
}

/**
 * Relasi ke Dompet (jika ini transaksi top-up).
 */
public function dompet()
{
    return $this->belongsTo(Dompet::class);
}
}