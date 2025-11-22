<?php

namespace App\Models\Pendidikan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RaporTemplate extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'pondok_id',        // <--- Ini yang menyebabkan error tadi
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

    /**
     * Relasi ke tabel Pondok
     */
    public function pondok()
    {
        return $this->belongsTo(\App\Models\Pondok::class);
    }

    /**
     * Helper untuk mendapatkan dimensi CSS saat cetak PDF
     */
    public function getCssPageSizeAttribute()
    {
        // Output contoh: "A4 portrait" atau "F4 landscape"
        return "{$this->ukuran_kertas} {$this->orientasi}";
    }
}