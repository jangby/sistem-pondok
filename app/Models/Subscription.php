<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'pondok_id', 
        'plan_id', 
        'starts_at', 
        'ends_at', 
        'status',
        'is_academic_active',
    ];

    // Otomatis ubah tanggal menjadi objek Carbon
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Relasi ke Plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Relasi ke Pondok
    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }
}