<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\User;
use App\Models\Pondok;
use Illuminate\Notifications\Notifiable; 

class Guru extends Model
{
    use HasFactory, BelongsToPondok, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Pastikan 'rfid_uid' ada di sini agar bisa disimpan.
     */
    protected $fillable = [
        'user_id', 
        'pondok_id', 
        'nip', 
        'telepon', 
        'alamat', 
        'tipe_jam_kerja',
        'rfid_uid', // <--- PENTING: Tambahkan ini
    ];

    /**
     * Relasi ke akun login guru.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }
    
    /**
     * Format nomor HP untuk WAHA (cth: 62812345678@c.us).
     * Digunakan jika Anda melakukan $guru->notify(...)
     */
    public function routeNotificationForWaha($notification)
    {
        // Ambil telepon dari tabel gurus (atau fallback ke user jika perlu)
        $telepon = $this->telepon; 

        if (!$telepon) {
            return null;
        }

        // Hapus spasi, +, -
        $phone = preg_replace('/[+\-\s]/', '', $telepon);

        // Ubah 08 di depan jadi 62
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Pastikan format akhiran @c.us (untuk personal chat)
        return $phone . '@c.us';
    }
}