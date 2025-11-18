<?php
namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory, BelongsToPondok;

    // Ganti 'kas' menjadi nama tabel Anda jika beda
    protected $table = 'kas'; 

    protected $fillable = [
        'pondok_id', 'user_id', 'setoran_id', 'tipe', 
        'deskripsi', 'nominal', 'tanggal_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'nominal' => 'float',
    ];

    // Relasi ke user (Bendahara) yang mencatat
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke setoran (jika ini pemasukan)
    public function setoran()
    {
        return $this->belongsTo(Setoran::class);
    }
}