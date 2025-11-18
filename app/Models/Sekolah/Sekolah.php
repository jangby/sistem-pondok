<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok; // Menggunakan Trait Anda yang sudah ada
use App\Models\User;
use App\Models\Pondok;

class Sekolah extends Model
{
    use HasFactory, BelongsToPondok; // Otomatis terhubung ke pondok

    protected $fillable = [
        'pondok_id', 'nama_sekolah', 'tingkat', 'kepala_sekolah',
    ];

    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }

    /**
     * Relasi ke user (Admin Sekolah & Guru) yang ditugaskan di sekolah ini.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'sekolah_user');
    }
    
    // Relasi ke data master sekolah ini
    public function mataPelajarans() { return $this->hasMany(MataPelajaran::class); }
    public function jadwalPelajarans() { return $this->hasMany(JadwalPelajaran::class); }
    
    // Relasi ke data konfigurasi
    public function wifiConfigs() { return $this->hasMany(SekolahWifi::class); }
    public function geofenceConfigs() { return $this->hasMany(SekolahLokasiGeofence::class); }
}