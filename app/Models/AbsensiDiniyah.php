<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiDiniyah extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'waktu_scan' => 'datetime',
        'tanggal' => 'date'
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalDiniyah::class, 'jadwal_diniyah_id');
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}