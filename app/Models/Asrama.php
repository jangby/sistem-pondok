<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Asrama extends Model
{
    use BelongsToPondok;

    protected $fillable = [
        'pondok_id', 'nama_asrama', 'komplek', 
        'ketua_asrama', 'kapasitas', 'jenis_kelamin'
    ];

    // Relasi ke Santri (Anggota)
    public function penghuni()
    {
        return $this->hasMany(Santri::class);
    }

    // Helper: Sisa Kapasitas
    public function getSisaKapasitasAttribute()
    {
        return $this->kapasitas - $this->penghuni()->count();
    }
}