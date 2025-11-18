<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Santri;

class AbsensiSiswaSekolah extends Model
{
    use HasFactory;

    protected $table = 'absensi_siswa_sekolah';

    protected $fillable = [
        'sekolah_id',
        'santri_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_masuk',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class); //
    }
}