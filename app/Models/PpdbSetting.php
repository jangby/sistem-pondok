<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran',
        'nama_gelombang',
        'tanggal_mulai',
        'tanggal_akhir',
        'biaya_pendaftaran',
        'deskripsi',
        'brosur',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'biaya_pendaftaran' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Helper untuk mengambil gelombang yang sedang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relasi ke Rincian Biaya
    public function biayas()
    {
        return $this->hasMany(PpdbBiaya::class);
    }
}