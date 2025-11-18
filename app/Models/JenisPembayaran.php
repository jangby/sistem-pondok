<?php

namespace App\Models;

use App\Traits\BelongsToPondok; // Pastikan trait ini ada
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    use HasFactory, BelongsToPondok; // Pastikan trait ini digunakan

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * INI ADALAH PERBAIKANNYA.
     */
    protected $fillable = [
        'name',
        'tipe',
        'pondok_id', // Sebaiknya tetap di-list
    ];

    /**
     * Relasi ke item-item rinciannya (uang makan, listrik, dll)
     */
    public function items()
    {
        return $this->hasMany(ItemPembayaran::class);
    }

    /**
     * Relasi ke tagihan yang menggunakan jenis ini
     */
    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    // Relasi ke Kelas (BARU)
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'jenis_pembayaran_kelas');
    }
}