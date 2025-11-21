<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalMengajar extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalDiniyah::class, 'jadwal_diniyah_id');
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }
}