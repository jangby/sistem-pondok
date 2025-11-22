<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPendidikan extends Model
{
    use HasFactory;

    // Menghubungkan ke tabel yang benar
    protected $table = 'jurnal_pendidikans';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi ke Ustadz (Penyimak)
     */
    public function ustadz()
    {
        return $this->belongsTo(Pendidikan\Ustadz::class, 'ustadz_id');
    }

    /**
     * Relasi ke Mapel (Opsional, misal hafalan kitab apa)
     */
    public function mapel()
    {
        return $this->belongsTo(Pendidikan\MapelDiniyah::class, 'mapel_diniyah_id');
    }

    /**
     * Helper untuk menampilkan teks rentang hafalan
     * Contoh output: "Al-Mulk: 1 - 5"
     */
    public function getRentangAttribute()
    {
        if ($this->start_at && $this->end_at) {
            return $this->start_at . ' - ' . $this->end_at;
        }
        return $this->start_at ?? '-';
    }
}