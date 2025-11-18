<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahWifi extends Model
{
    use HasFactory;
    protected $table = 'sekolah_wifi';
    protected $fillable = ['sekolah_id', 'nama_wifi_ssid', 'bssid'];
    
    public function sekolah() { return $this->belongsTo(Sekolah::class); }
}