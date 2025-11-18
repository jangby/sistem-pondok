<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id', 'nama_mapel', 'kode_mapel',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}