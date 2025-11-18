<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Santri;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id', 'santri_id', 'mata_pelajaran_id', 'guru_user_id',
        'kegiatan_akademik_id', 'tipe_nilai', 'nilai', 'keterangan',
    ];

    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class); }
    public function santri() { return $this->belongsTo(Santri::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_user_id'); }
    public function kegiatanAkademik() { return $this->belongsTo(KegiatanAkademik::class); }
}