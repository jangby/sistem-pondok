<?php

namespace App\Models\Pendidikan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pondok;

class KartuUjianTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'pondok_id',
        'nama_template',
        'konten_html',
        'ukuran_kertas',
        'orientasi',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',
        'is_active',
    ];

    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }

    public function getCssPageSizeAttribute()
    {
        return "{$this->ukuran_kertas} {$this->orientasi}";
    }
}