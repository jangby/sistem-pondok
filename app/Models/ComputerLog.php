<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerLog extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // TAMBAHKAN BAGIAN INI
    protected $casts = [
        'last_seen' => 'datetime', // Memaksa kolom ini jadi objek Waktu
    ];
}