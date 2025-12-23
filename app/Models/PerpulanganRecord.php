<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PerpulanganRecord extends Model
{
    protected $table = 'perpulangan_records';

    protected $fillable = [
        'perpulangan_event_id',
        'santri_id',
        'qr_token',
        'status',
        'waktu_keluar',
        'waktu_kembali',
        'is_late',
        'petugas_keluar_id',
        'petugas_masuk_id',
    ];

    protected $casts = [
        'waktu_keluar' => 'datetime',
        'waktu_kembali' => 'datetime',
        'is_late' => 'boolean',
    ];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(PerpulanganEvent::class, 'perpulangan_event_id');
    }

    // Relasi ke Santri (PENTING: Memastikan akses data santri benar)
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    // Helper untuk petugas scan
    public function petugasKeluar()
    {
        return $this->belongsTo(User::class, 'petugas_keluar_id');
    }

    public function petugasMasuk()
    {
        return $this->belongsTo(User::class, 'petugas_masuk_id');
    }
    
    // Status text label untuk UI badge (Mobile design friendly)
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            0 => 'Belum Jalan',
            1 => 'Sedang Pulang',
            2 => 'Sudah Kembali',
            default => 'Unknown'
        };
    }
    
    // Warna badge untuk UI Emerald Theme
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            0 => 'bg-gray-100 text-gray-600',
            1 => 'bg-amber-100 text-amber-700', // Warning color untuk yang sedang diluar
            2 => 'bg-emerald-100 text-emerald-700', // Success color
            default => 'bg-gray-100'
        };
    }
}