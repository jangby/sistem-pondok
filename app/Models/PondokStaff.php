<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondokStaff extends Model
{
    use HasFactory;

    // 1. Definisikan Nama Tabel secara Eksplisit
    // (Penting: karena migrasi Anda bernama 'pondok_staff' tapi Laravel mencari 'pondok_staffs')
    protected $table = 'pondok_staff'; 

    // 2. Izinkan Pengisian Kolom (Mass Assignment)
    protected $fillable = [
        'user_id',
        'pondok_id',
        'role', // Jika ada kolom role di tabel ini
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pondok
    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }
}