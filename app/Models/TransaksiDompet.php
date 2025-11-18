<?php

//namespace App; // <-- HATI-HATI: Jika model lain di App\Models, sesuaikan ini
namespace App\Models; // <-- Gunakan ini jika folder Anda 'app/Models'

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDompet extends Model
{
    use HasFactory;

    protected $table = 'transaksi_dompets';

    protected $fillable = [
        'dompet_id',
        'warung_id',
        'user_id_pencatat',
        'tipe',
        'nominal',
        'saldo_sebelum',
        'saldo_setelah',
        'keterangan',
    ];

    // Relasi ke dompet
    public function dompet()
    {
        return $this->belongsTo(Dompet::class);
    }

    // Relasi ke warung (jika 'jajan')
    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    // Relasi ke pencatat (admin/ortu)
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'user_id_pencatat');
    }
}