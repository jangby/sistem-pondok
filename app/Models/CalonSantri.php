<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonSantri extends Model
{
    use HasFactory;

    /**
     * Kolom yang tidak boleh diisi secara massal (hanya ID).
     * Sisanya boleh diisi (mass assignable).
     */
    protected $guarded = ['id'];

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'total_sudah_bayar' => 'decimal:2', // Pastikan angka desimal presisi
    ];

    /**
     * Relasi ke Model User (Akun Login Calon Santri).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Pengaturan PPDB (Gelombang Pendaftaran).
     */
    public function ppdbSetting()
    {
        return $this->belongsTo(PpdbSetting::class);
    }

    /**
     * Helper: Ambil daftar rincian biaya sesuai jenjang santri ini
     */
    public function getRincianBiayaAttribute()
    {
        if (!$this->ppdbSetting) return collect([]);

        return $this->ppdbSetting->biayas->where('jenjang', $this->jenjang);
    }

    /**
     * Helper: Hitung Total Tagihan (Sum dari rincian biaya)
     */
    public function getTotalBiayaAttribute()
    {
        return $this->rincian_biaya->sum('nominal');
    }

    /**
     * Override Helper Sisa Tagihan yang lama
     */
    public function getSisaTagihanAttribute()
    {
        return max(0, $this->total_biaya - $this->total_sudah_bayar);
    }

    /**
     * Override Helper Persentase
     */
    public function getPersentasePembayaranAttribute()
    {
        if ($this->total_biaya <= 0) return 100;
        $persen = ($this->total_sudah_bayar / $this->total_biaya) * 100;
        return min(100, round($persen));
    }
}