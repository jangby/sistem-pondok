<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarungPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'warung_id',
        'nominal',
        'status',
        'keterangan',
        'catatan_admin',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }
}