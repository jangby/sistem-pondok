<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pondok extends Model
{

    protected $fillable = [
        'name',
        'address',
        'phone',
        'status',
    ];


    // Relasi ke santri-santrinya
public function santris()
{
    return $this->hasMany(Santri::class);
}

// Relasi ke orang tua santrinya
public function orangTuas()
{
    return $this->hasMany(OrangTua::class);
}

/**
     * Relasi ke staf (admin/bendahara) yang terdaftar di pondok ini.
     */
    public function staff()
    {
        return $this->hasMany(PondokStaff::class);
    }

    public function subscription()
{
    // 1 pondok hanya punya 1 langganan aktif
    return $this->hasOne(Subscription::class)->latestOfMany();
}

/**
     * Relasi ke semua unit sekolah yang dimiliki pondok ini.
     */
    public function sekolahs(): HasMany
    {
        return $this->hasMany(\App\Models\Sekolah\Sekolah::class);
    }

    /**
     * Relasi ke semua guru yang ada di pondok ini.
     */
    public function gurus(): HasMany
    {
        return $this->hasMany(\App\Models\Sekolah\Guru::class);
    }
}
