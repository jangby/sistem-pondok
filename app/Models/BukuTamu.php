<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_tamu', 'instansi_asal', 'bertemu_dengan', 'keperluan', 'no_hp', 'jam_masuk', 'jam_keluar'
    ];
}