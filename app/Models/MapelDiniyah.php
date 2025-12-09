<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class MapelDiniyah extends Model
{
    use BelongsToPondok;

    protected $guarded = ['id'];

    // Mapel ini punya konfigurasi ujian apa saja?
    protected $casts = [
        'uji_tulis' => 'boolean',
        'uji_lisan' => 'boolean',
        'uji_praktek' => 'boolean',
        'uji_hafalan' => 'boolean',
    ];

    public function jadwals()
    {
        return $this->hasMany(JadwalDiniyah::class);
    }
}