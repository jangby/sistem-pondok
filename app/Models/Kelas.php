<?php
namespace App\Models;
use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Kelas extends Model
{
    use BelongsToPondok;
    protected $table = 'kelas';
    protected $fillable = ['pondok_id', 'nama_kelas', 'tingkat'];

    // Relasi ke Jenis Pembayaran (untuk nanti)
    public function jenisPembayarans() {
        return $this->belongsToMany(JenisPembayaran::class, 'jenis_pembayaran_kelas');
    }

    /**
     * Relasi ke semua jadwal pelajaran yang ada di kelas ini.
     */
    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(\App\Models\Sekolah\JadwalPelajaran::class);
    }

    public function santris(): HasMany
    {
        // Relasi ini merujuk ke 'kelas_id' di tabel 'santris'
        return $this->hasMany(\App\Models\Santri::class); //
    }
}