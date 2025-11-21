<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Mustawa extends Model
{
    use BelongsToPondok;

    protected $guarded = ['id'];

    public function waliUstadz()
    {
        return $this->belongsTo(Ustadz::class, 'wali_ustadz_id');
    }

    public function jadwals()
    {
        return $this->hasMany(JadwalDiniyah::class);
    }

    public function santris() { return $this->hasMany(Santri::class); }
}