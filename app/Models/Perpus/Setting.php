<?php

namespace App\Models\Perpus;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Setting extends Model
{
    use BelongsToPondok;

    protected $table = 'perpus_settings';

    protected $fillable = [
        'pondok_id',
        'denda_per_hari',
        'batas_hari_pinjam',
        'denda_rusak_ringan',
        'denda_rusak_berat',
    ];

    /**
     * Ambil setting milik pondok yang sedang login.
     * Jika belum ada, return nilai default.
     */
    public static function getRules($pondokId)
    {
        return self::firstOrCreate(
            ['pondok_id' => $pondokId],
            [
                'denda_per_hari' => 1000,
                'batas_hari_pinjam' => 7,
                'denda_rusak_ringan' => 20000,
                'denda_rusak_berat' => 50000,
            ]
        );
    }
}