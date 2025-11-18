<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiPembayaran extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabelnya secara eksplisit agar sesuai dengan migrasi.
     */
    protected $table = 'alokasi_pembayarans';

    /**
     * Kolom yang boleh diisi secara massal (mass assignable).
     * INI ADALAH PERBAIKANNYA.
     */
    protected $fillable = [
        'pembayaran_transaksi_id',
        'tagihan_detail_id',
        'nominal_alokasi',
    ];

    /**
     * Relasi ke transaksi induknya.
     */
    public function pembayaranTransaksi()
    {
        return $this->belongsTo(PembayaranTransaksi::class);
    }

    /**
     * Relasi ke item tagihan yang dialokasikan.
     */
    public function tagihanDetail()
    {
        return $this->belongsTo(TagihanDetail::class);
    }
}