<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class JadwalDiniyah extends Model
{
    use BelongsToPondok;

    protected $guarded = ['id'];

    public function mustawa()
    {
        return $this->belongsTo(Mustawa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MapelDiniyah::class, 'mapel_diniyah_id');
    }

    public function ustadz()
    {
        return $this->belongsTo(Ustadz::class);
    }

    // Satu jadwal punya banyak data absensi (per hari)
    public function absensis()
    {
        return $this->hasMany(AbsensiDiniyah::class);
    }
}