<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Santri; // Menggunakan model Santri Anda yang sudah ada

class AbsensiSiswaPelajaran extends Model
{
    use HasFactory;
    
    // Nama tabel custom jika diperlukan (sesuai migrasi)
    protected $table = 'absensi_siswa_pelajaran';

    protected $fillable = [
        'absensi_pelajaran_id', 'santri_id', 'status', 
        'jam_hadir', 'keterangan',
    ];

    public function absensiPelajaran() { return $this->belongsTo(AbsensiPelajaran::class); }
    public function santri() { return $this->belongsTo(Santri::class); } // Relasi ke model Santri lama
}