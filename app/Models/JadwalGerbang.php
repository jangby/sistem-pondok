<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalGerbang extends Model
{
    use HasFactory;
    protected $fillable = ['santri_id', 'hari']; // Ubah user_id jadi santri_id

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }
}