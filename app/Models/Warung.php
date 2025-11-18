<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    use HasFactory, BelongsToPondok; // Trait BelongsToPondok otomatis aktif

    protected $table = 'warungs';

    protected $fillable = [
        'pondok_id', 
        'user_id', 
        'nama_warung', 
        'status'
    ];

    // Relasi ke akun login warung
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksiDompets()
    {
        return $this->hasMany(TransaksiDompet::class);
    }

    public function payouts()
    {
        return $this->hasMany(\App\Models\WarungPayout::class); // Pastikan model WarungPayout ada
    }

   /**
     * LOGIKA SALDO OTOMATIS
     * Saldo = Total Pemasukan (Jajan) - Total Penarikan (Sukses & Pending)
     */
    public function getSaldoAttribute()
    {
        // 1. Hitung Pemasukan dari Jajan
        // Di database tercatat minus (karena mengurangi saldo anak), jadi kita positifkan pakai abs()
        $pemasukan = abs($this->transaksiDompets()
            ->where('tipe', 'jajan')
            ->sum('nominal'));

        // 2. Hitung Penarikan (Yang sudah cair DAN yang masih pending)
        // Kita kurangi yang pending juga supaya tidak bisa ditarik double
        $penarikan = $this->payouts()
            ->whereIn('status', ['approved', 'pending'])
            ->sum('nominal');

        return $pemasukan - $penarikan;
    }
}