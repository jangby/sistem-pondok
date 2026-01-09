<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbDistribusiDana extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'list_santri_id' => 'array',
        'tanggal' => 'date'
    ];

    // Relasi ke User (Petugas yang input)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Setting (Gelombang)
    public function ppdbSetting()
    {
        return $this->belongsTo(PpdbSetting::class);
    }
}