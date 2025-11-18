<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'name', 
        'price_monthly', 
        'price_yearly', 
        'features' // Kita bisa gunakan ini nanti untuk Paket Premium
    ];

    // Tentukan tipe data
    protected $casts = [
        'price_monthly' => 'integer',
        'price_yearly' => 'integer',
        'features' => 'array', // Disimpan sebagai JSON
    ];
}