<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UujPayout extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}