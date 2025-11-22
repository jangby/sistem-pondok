<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPendidikan extends Model
{
    use HasFactory;

    protected $table = 'jurnal_pendidikans';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * PERBAIKAN: Hapus prefix 'Pendidikan\' karena model ada di App\Models
     */
    public function ustadz()
    {
        // SEBELUMNYA (Error): return $this->belongsTo(Pendidikan\Ustadz::class, 'ustadz_id');
        // SEKARANG (Benar):
        return $this->belongsTo(Ustadz::class, 'ustadz_id');
    }

    /**
     * PERBAIKAN: Hapus prefix 'Pendidikan\' untuk Mapel juga
     */
    public function mapel()
    {
        // SEBELUMNYA (Mungkin Error juga): return $this->belongsTo(Pendidikan\MapelDiniyah::class, ...);
        // SEKARANG (Benar):
        return $this->belongsTo(MapelDiniyah::class, 'mapel_diniyah_id');
    }

    public function getRentangAttribute()
    {
        if ($this->start_at && $this->end_at) {
            return $this->start_at . ' - ' . $this->end_at;
        }
        return $this->start_at ?? '-';
    }
}