<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok; 

class Ustadz extends Model
{
    use BelongsToPondok; // Agar otomatis terfilter per pondok

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Jadwal mengajar ustadz ini
    public function jadwals()
    {
        return $this->hasMany(JadwalDiniyah::class);
    }

    // Kelas yang di-wali-kan oleh ustadz ini
    public function mustawaWali()
    {
        return $this->hasMany(Mustawa::class, 'wali_ustadz_id');
    }
}