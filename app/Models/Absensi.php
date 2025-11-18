<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'pondok_id',
        'santri_id',
        'kategori',
        'nama_kegiatan',
        'status',
        'tanggal',
        'waktu_catat',
        'pencatat_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'pencatat_id');
    }
}