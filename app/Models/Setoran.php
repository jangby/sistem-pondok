<?php
namespace App\Models;

use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'pondok_id', 'admin_id_penyetor', 'bendahara_id_penerima',
        'tanggal_setoran', 'total_setoran', 
        'kategori_setoran', // <-- TAMBAHKAN INI
        'catatan', 'dikonfirmasi_pada',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id_penyetor');
    }

    public function bendaharaPenerima()
    {
        return $this->belongsTo(User::class, 'bendahara_id_penerima');
    }

    public function transaksi()
    {
        return $this->hasMany(PembayaranTransaksi::class);
    }
}