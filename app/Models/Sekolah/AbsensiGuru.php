<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_user_id', 'sekolah_id', 'tanggal', 'status', 
        'jam_masuk', 'jam_pulang', 'verifikasi_masuk', 
        'verifikasi_pulang', 'keterangan',
    ];

    public function guru() { return $this->belongsTo(User::class, 'guru_user_id'); }
    public function sekolah() { return $this->belongsTo(Sekolah::class); }
}