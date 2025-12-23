<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerpulanganEvent extends Model
{
    protected $table = 'perpulangan_events';
    
    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'tanggal_akhir',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke record santri
    public function records()
    {
        return $this->hasMany(PerpulanganRecord::class, 'perpulangan_event_id');
    }

    // Hitung statistik sederhana
    public function getStatistikAttribute()
    {
        return [
            'total_peserta' => $this->records()->count(),
            'sudah_pulang' => $this->records()->where('status', 1)->count(),
            'sudah_kembali' => $this->records()->where('status', 2)->count(),
            'belum_kembali' => $this->records()->where('status', 1)->count(), // Sedang di rumah
        ];
    }
}