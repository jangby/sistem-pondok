<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesehatanSantri extends Model
{
    use HasFactory, BelongsToPondok;

    protected $table = 'kesehatan_santris';

    protected $fillable = [
        'pondok_id',
        'santri_id',
        'keluhan',
        'tindakan',
        'status',
        'tanggal_sakit',
        'tanggal_sembuh',
    ];

    protected $casts = [
        'tanggal_sakit' => 'date',
        'tanggal_sembuh' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}