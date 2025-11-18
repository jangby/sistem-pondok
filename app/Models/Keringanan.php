<?php

namespace App\Models;

use App\Traits\BelongsToPondok; // Pastikan trait ini ada
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keringanan extends Model
{
    use HasFactory, BelongsToPondok; // Pastikan trait ini digunakan

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * INI ADALAH PERBAIKANNYA.
     */
    protected $fillable = [
        'santri_id',
        'jenis_pembayaran_id',
        'tipe_keringanan',
        'nilai',
        'berlaku_mulai',
        'berlaku_sampai',
        'keterangan',
        'pondok_id', // Sebaiknya tetap di-list
    ];

    /**
     * Relasi ke santri yang mendapat keringanan.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke jenis pembayaran yang diberi keringanan.
     */
    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }
}