<?php

namespace App\Models\Perpus;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\Santri;
use App\Models\User;

class Kunjungan extends Model
{
    use BelongsToPondok;

    protected $table = 'perpus_kunjungans';

    protected $fillable = [
        'pondok_id',
        'santri_id',
        'user_id', // Petugas yang scan (opsional)
        'waktu_berkunjung',
        'keperluan',
    ];

    protected $casts = [
        'waktu_berkunjung' => 'datetime',
    ];

    // Relasi ke Santri (Pengunjung)
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    // Relasi ke Petugas (User)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}