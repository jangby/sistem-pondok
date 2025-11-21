<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiUjianDiniyah extends Model
{
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}