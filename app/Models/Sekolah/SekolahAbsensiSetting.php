<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahAbsensiSetting extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'sekolah_absensi_settings'; // Sesuai dengan file migrasi kita

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'sekolah_id',
        'jam_masuk',
        'batas_telat',
        'jam_pulang_awal',
        'jam_pulang_akhir',
        'hari_kerja',
    ];

    /**
     * Atribut yang harus di-cast ke tipe aslinya.
     *
     * @var array
     */
    protected $casts = [
        'hari_kerja' => 'array', // Penting, karena kita simpan sebagai JSON
    ];

    /**
     * Relasi ke sekolah pemilik pengaturan ini.
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}