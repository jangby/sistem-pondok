<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class OrangTua extends Model
{
    use HasFactory, BelongsToPondok, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'nik',
        'pekerjaan',
        'user_id', // Kita juga masukkan user_id untuk nanti saat membuatkan login
        'pondok_id', // Walaupun trait mengisi, ada baiknya tetap di-list
    ];
    // Relasi ke akun login-nya
public function user()
{
    return $this->belongsTo(User::class);
}

// Relasi ke pondok-nya
public function pondok()
{
    return $this->belongsTo(Pondok::class);
}

// Relasi ke anak-anaknya (santri)
public function santris()
{
    return $this->hasMany(Santri::class);
}

/**
     * Format nomor HP untuk WAHA (cth: 62812345678@c.us).
     */
    public function routeNotificationForWaha($notification)
    {
        if (!$this->phone) {
            return null;
        }
        // Hapus spasi, +, -, ganti 08 di depan jadi 62
        $phone = preg_replace('/[+\-\s]/', '', $this->phone);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        return $phone . '@c.us';
    }
}
