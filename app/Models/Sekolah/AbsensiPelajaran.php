<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_pelajaran_id', 'tanggal', 'status_guru', 
        'jam_guru_masuk_kelas', 'materi_pembahasan',
        'guru_pengganti_user_id', 'is_substitute'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwalPelajaran() { return $this->belongsTo(JadwalPelajaran::class); }
    
    /**
     * Relasi ke detail absensi siswa di jam pelajaran ini.
     */
    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswaPelajaran::class);
    }

    /**
     * Relasi ke Guru Pengganti
     */
    public function guruPengganti()
    {
        return $this->belongsTo(\App\Models\User::class, 'guru_pengganti_user_id');
    }
}