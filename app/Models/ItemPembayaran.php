<?php

namespace App\Models;

use App\Traits\BelongsToPondok; // Pastikan trait ini ada
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPembayaran extends Model
{
    use HasFactory, BelongsToPondok; // Pastikan trait ini digunakan

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * INI ADALAH PERBAIKANNYA.
     */
    protected $fillable = [
        'nama_item',
        'nominal',
        'prioritas',
        'jenis_pembayaran_id',
        'pondok_id',
    ];

    /**
     * Relasi ke induknya (Jenis Pembayaran).
     */
    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }
}