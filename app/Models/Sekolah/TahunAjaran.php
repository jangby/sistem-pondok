<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class TahunAjaran extends Model
{
    use HasFactory, BelongsToPondok;
    
    protected $fillable = [
        'pondok_id', 'nama_tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai', 'is_active',
    ];
}