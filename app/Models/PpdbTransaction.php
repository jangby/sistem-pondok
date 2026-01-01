<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbTransaction extends Model
{
    use HasFactory;
    
    // Pastikan kolom baru bisa diisi
    protected $fillable = [
        'calon_santri_id',
        'order_id',
        'gross_amount',
        'biaya_admin',    // Baru
        'payment_type',   // Baru
        'payment_code',   // Baru
        'payment_url',
        'status',
        'snap_token'
    ];

    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }
}