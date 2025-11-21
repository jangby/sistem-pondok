<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjianDiniyah extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mustawa()
    {
        return $this->belongsTo(Mustawa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MapelDiniyah::class, 'mapel_diniyah_id');
    }

    public function pengawas()
    {
        return $this->belongsTo(Ustadz::class, 'pengawas_id');
    }

    // Tambahkan ini di dalam class JadwalUjianDiniyah
public function absensi()
{
    return $this->hasMany(AbsensiUjianDiniyah::class);
}
}