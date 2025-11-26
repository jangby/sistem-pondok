<?php

namespace App\Models\Perpus;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\Santri;
use App\Models\User;

class Peminjaman extends Model
{
    use BelongsToPondok;

    protected $table = 'perpus_peminjamans';

    protected $fillable = [
        'kode_transaksi',
        'pondok_id',
        'santri_id',
        'buku_id',
        'petugas_pinjam',
        'petugas_kembali',
        'tgl_pinjam',
        'tgl_wajib_kembali',
        'tgl_kembali_real',
        'status',          // 'dipinjam', 'kembali', 'hilang'
        'kondisi_kembali', // 'baik', 'rusak_ringan', 'rusak_berat'
        'denda_keterlambatan',
        'denda_kerusakan',
        'biaya_ganti_buku',
        'catatan',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_wajib_kembali' => 'date',
        'tgl_kembali_real' => 'date',
        'denda_keterlambatan' => 'decimal:2',
        'denda_kerusakan' => 'decimal:2',
        'biaya_ganti_buku' => 'decimal:2',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id')->withTrashed(); // Tetap load meski buku dihapus
    }

    // Petugas saat meminjamkan
    public function adminPinjam()
    {
        return $this->belongsTo(User::class, 'petugas_pinjam');
    }

    // Petugas saat mengembalikan
    public function adminKembali()
    {
        return $this->belongsTo(User::class, 'petugas_kembali');
    }

    public function peminjam()
{
    // Jika Anda menyimpan ID di kolom 'peminjam_id' dan tipe di 'tipe_peminjam' (Polimorfik)
    return $this->morphTo(); 
    
    // ATAU jika Anda menyimpan langsung di 'santri_id' (Relasi Biasa)
    // return $this->belongsTo(\App\Models\Santri::class, 'santri_id');
}
}