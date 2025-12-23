<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbTransaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }
}