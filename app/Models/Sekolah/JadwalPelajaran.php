<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kelas; // Menggunakan model Kelas Anda yang sudah ada

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id', 'sekolah_id', 'kelas_id', 
        'mata_pelajaran_id', 'guru_user_id', 'hari', 
        'jam_mulai', 'jam_selesai',
    ];

    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class); }
    public function sekolah() { return $this->belongsTo(Sekolah::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); } // Relasi ke model Kelas lama
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_user_id'); } // Relasi ke User Guru

    /**
     * Relasi ke log absensi pelajaran (One to Many, tapi biasanya 1 per hari).
     */
    public function absensiPelajaran()
    {
        return $this->hasMany(AbsensiPelajaran::class);
    }
}