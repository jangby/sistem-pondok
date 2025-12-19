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
    'nik',
    'no_kk',
    'tahun_masuk',
    'rfid_uid',
    'qrcode_token',
    'full_name',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'golongan_darah',
    'riwayat_penyakit',
    'kelas_id',
    'mustawa_id',
    'status',
    'orang_tua_id',
    'pondok_id',
    'asrama_id',
    // --- TAMBAHAN BARU ---
    'alamat', 'rt', 'rw', 'desa', 'kecamatan', 'kode_pos',
    'nama_ayah', 'thn_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nik_ayah',
    'nama_ibu', 'thn_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'nik_ibu',
];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke pondok-nya
public function pondok()
{
    return $this->belongsTo(Pondok::class);
}

// PERBAIKAN UTAMA: Relasi Orang Tua diperjelas key-nya
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id', 'id');
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

    // Relasi ke Absensi Pesantren
public function absensiDiniyah()
{
    return $this->hasMany(AbsensiDiniyah::class);
}

// Relasi ke Nilai Rapor Pesantren
public function nilaiPesantren()
{
    return $this->hasMany(NilaiPesantren::class);
}

// Relasi ke Program Unggulan (Many-to-Many)
public function programUnggulan()
{
    return $this->belongsToMany(ProgramUnggulan::class, 'program_unggulan_members')
                ->withPivot('joined_at')
                ->withTimestamps();
}

// Relasi ke Jurnal (Hafalan/Catatan)
public function jurnalPendidikan()
{
    return $this->hasMany(JurnalPendidikan::class);
}

// Tambahkan Relasi ini
    public function mustawa()
    {
        return $this->belongsTo(Mustawa::class);
    }

    /**
 * Accessor untuk menghitung persentase kelengkapan data
 * Cara panggil di blade: $santri->persentase_lengkap
 */
public function getPersentaseLengkapAttribute()
{
    // Daftar kolom yang dianggap penting/wajib
    $fields = [
        'nama_lengkap', 'nisn', 'nik', 'no_kk', 
        'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        'nama_ayah', 'nama_ibu', 'no_hp_ayah', // Sesuaikan dengan kolom real di DB Anda
    ];

    $filled = 0;
    foreach ($fields as $field) {
        // Cek apakah field ini ada isinya (tidak null dan tidak kosong)
        if (!empty($this->{$field})) {
            $filled++;
        }
    }

    return count($fields) > 0 ? round(($filled / count($fields)) * 100) : 0;
}
}
