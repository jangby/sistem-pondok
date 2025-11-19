<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanAkademik extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected $fillable = [
        'tahun_ajaran_id', 'sekolah_id', 'nama_kegiatan', 
        'tipe', 'tanggal_mulai', 'tanggal_selesai', 'keterangan',
    ];
    
    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class); }
    public function sekolah() { return $this->belongsTo(Sekolah::class); }
}