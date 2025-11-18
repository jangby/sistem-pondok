<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahHariLibur extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'sekolah_hari_libur'; // Sesuai dengan file migrasi kita

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'sekolah_id',
        'tanggal',
        'keterangan',
    ];

    /**
     * Atribut yang harus di-cast ke tipe aslinya.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date', // Otomatis cast ke objek Carbon
    ];

    /**
     * Relasi ke sekolah pemilik hari libur ini.
     */
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}