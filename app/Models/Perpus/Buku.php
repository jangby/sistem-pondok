<?php

namespace App\Models\Perpus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToPondok; // Trait agar otomatis filter per pondok

class Buku extends Model
{
    use SoftDeletes, BelongsToPondok;

    protected $table = 'perpus_bukus';

    protected $fillable = [
        'pondok_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kode_buku',
        'stok_total',
        'stok_tersedia',
        'lokasi_rak',
        'deskripsi',
        'foto_cover',
    ];

    // Relasi ke riwayat peminjaman buku ini
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // Helper: Cek apakah buku bisa dipinjam
    public function getIsAvailableAttribute()
    {
        return $this->stok_tersedia > 0;
    }
}