<?php

namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Santri extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'nis',
        'rfid_uid',       // <-- Tambahkan ini
        'qrcode_token',
        'full_name',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'riwayat_penyakit',
        'kelas_id',
        'status',
        'orang_tua_id',
        'pondok_id',
        'asrama_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke pondok-nya
public function pondok()
{
    return $this->belongsTo(Pondok::class);
}

// Relasi ke orang tuanya
public function orangTua()
{
    return $this->belongsTo(OrangTua::class);
}

// Relasi ke semua tagihannya
public function tagihans()
{
    return $this->hasMany(Tagihan::class);
}

// Relasi baru untuk mendapatkan User (melalui Orang Tua)
public function user()
{
    // Ini mengasumsikan Santri berelasi ke OrangTua (orang_tua_id) 
    // dan OrangTua berelasi ke User (user_id)
    return $this->hasOneThrough(
        User::class,     // Model akhir yang ingin dicapai
        OrangTua::class, // Model perantara
        'id',            // Foreign key pada tabel OrangTua yang terhubung ke model perantara (Pondok)
        'id',            // Foreign key pada tabel User yang terhubung ke model akhir
        'orang_tua_id',  // Local key pada Santri
        'user_id'        // Local key pada OrangTua (perantara)
    );
}
public function tagihanDetails()
    {
        return $this->hasManyThrough(TagihanDetail::class, Tagihan::class);
    }

    // 2. Tambahkan relasi
public function kelas()
{
    return $this->belongsTo(Kelas::class);
}

/**
 * Relasi ke dompet uang jajan santri.
 */
public function dompet()
{
    return $this->hasOne(Dompet::class);
}

    /**
     * Relasi ke riwayat deposit uang jajan
     */
    public function uangJajanMasuk()
    {
        return $this->hasMany(UangJajanMasuk::class)->latest();
    }

    /**
     * Relasi ke riwayat penarikan uang jajan
     */
    public function uangJajanKeluar()
    {
        return $this->hasMany(UangJajanKeluar::class)->latest();
    }

    // Relasi pembayaran (jika belum ada)
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class)->latest();
    }

    public function riwayatKesehatan()
    {
        return $this->hasMany(KesehatanSantri::class)->latest();
    }

    public function perizinans()
    {
        return $this->hasMany(Perizinan::class)->latest();
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class)->latest();
    }

    // Cek apakah sedang haid (Status Aktif)
    public function haidAktif()
    {
        return $this->hasOne(Haid::class)->whereNull('tgl_selesai')->latest();
    }
    
    // Riwayat Haid
    public function riwayatHaid()
    {
        return $this->hasMany(Haid::class)->latest();
    }

    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }

    /**
     * Relasi ke semua nilai akademik santri.
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(\App\Models\Sekolah\Nilai::class);
    }

    /**
     * Relasi ke semua absensi pelajaran santri.
     */
    public function absensiPelajarans(): HasMany
    {
        return $this->hasMany(\App\Models\Sekolah\AbsensiSiswaPelajaran::class);
    }
}
