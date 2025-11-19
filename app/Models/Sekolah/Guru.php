<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\User;
use App\Models\Pondok;
use Illuminate\Notifications\Notifiable; // <-- 1. TAMBAHKAN IMPORT INI

class Guru extends Model
{
    //
    use HasFactory, BelongsToPondok, Notifiable; // <-- 2. TAMBAHKAN 'Notifiable'

    protected $fillable = [
        'user_id', 'pondok_id', 'nip', 'telepon', 'alamat', 'tipe_jam_kerja',
    ];

    /**
     * Relasi ke akun login guru.
     */
    public function user()
    {
        return $this->belongsTo(User::class); //
    }

    public function pondok()
    {
        return $this->belongsTo(Pondok::class); //
    }
    
    /*
    |--------------------------------------------------------------------------
    | 3. TAMBAHKAN FUNGSI BARU DI BAWAH INI
    |--------------------------------------------------------------------------
    | (Meniru pola dari app/Models/OrangTua.php)
    */
    
    /**
     * Format nomor HP untuk WAHA (cth: 62812345678@c.us).
     */
    public function routeNotificationForWaha($notification)
    {
        if (!$this->telepon) {
            return null;
        }
        // Hapus spasi, +, -, ganti 08 di depan jadi 62
        $phone = preg_replace('/[+\-\s]/', '', $this->telepon);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        return $phone . '@c.us';
    }
}