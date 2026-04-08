<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGerbang extends Model
{
    use HasFactory;
    protected $fillable = ['santri_id', 'tanggal', 'absen_pagi', 'absen_sore']; // Ubah user_id jadi santri_id

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}