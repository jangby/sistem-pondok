<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'pondok_id',
        'santri_id',
        'jenis_izin',
        'alasan',
        'tgl_mulai',
        'tgl_selesai_rencana',
        'tgl_kembali_realisasi',
        'status',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_selesai_rencana' => 'datetime',
        'tgl_kembali_realisasi' => 'datetime',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}