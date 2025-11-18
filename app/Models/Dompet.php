<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dompet extends Model
{
    use HasFactory, BelongsToPondok;

    protected $table = 'dompets';

    protected $fillable = [
        'santri_id',
        'pondok_id',
        'barcode_token',
        'saldo',
        'pin',
        'daily_spending_limit',
        'status',
    ];

    // Relasi ke santri pemilik dompet
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    // --- TAMBAHKAN INI AGAR ERROR HILANG ---
    // Relasi ke riwayat transaksi
    public function transaksiDompets()
    {
        return $this->hasMany(TransaksiDompet::class)->latest();
    }
}