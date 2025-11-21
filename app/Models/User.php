<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- TAMBAHKAN BARIS INI
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // <-- TAMBAHKAN 'HasRoles' DI SINI

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Format nomor HP untuk WAHA (cth: 62812345678@c.us).
     * Ini akan membaca kolom 'telepon' baru kita.
     */
    public function routeNotificationForWaha($notification)
    {
        if (!$this->telepon) {
            return null;
        }
        $phone = preg_replace('/[+\-\s]/', '', $this->telepon);
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }
        return $phone . '@c.us';
    }

    /**
 * Relasi untuk staf (Admin Pondok / Bendahara).
 */
public function pondokStaff()
{
    // Seorang User punya satu data staff (jika dia staff)
    return $this->hasOne(PondokStaff::class);
}

public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    public function warung()
{
    return $this->hasOne(Warung::class);
}

public function santris()
{
    // Satu User (Orangtua) bisa punya banyak Santri
    return $this->hasMany(Santri::class, 'orang_tua_id'); 
    // pastikan ada kolom 'user_id_orangtua' di tabel 'santris'
}

// Ganti fungsi-fungsi ini
public function guru(): HasOne
    {
        return $this->hasOne(\App\Models\Sekolah\Guru::class); //
    }

    
    public function sekolahs(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Sekolah\Sekolah::class, 'sekolah_user');
    }

    public function ustadz()
{
    return $this->hasOne(Ustadz::class);
}
}