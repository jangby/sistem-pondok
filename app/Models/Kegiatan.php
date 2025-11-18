<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Kegiatan extends Model
{
    use BelongsToPondok;

    protected $fillable = [
        'pondok_id', 'nama_kegiatan', 
        'frekuensi', 'detail_waktu', 'jam_mulai', 'jam_selesai',
        'tipe_peserta', 'detail_peserta'
    ];

    protected $casts = [
        'detail_waktu' => 'array',
        'detail_peserta' => 'array',
    ];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}