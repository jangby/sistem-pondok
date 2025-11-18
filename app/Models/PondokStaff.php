<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondokStaff extends Model
{
    use HasFactory;

    // Tentukan nama tabelnya secara eksplisit
    protected $table = 'pondok_staff';

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'pondok_id',
    ];

    // Definisikan relasi (ini akan sangat berguna)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }
}