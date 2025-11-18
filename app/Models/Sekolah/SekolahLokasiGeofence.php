<?php

namespace App\Models\Sekolah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SekolahLokasiGeofence extends Model
{
    use HasFactory;
    protected $table = 'sekolah_lokasi_geofence';
    protected $fillable = ['sekolah_id', 'nama_lokasi', 'latitude', 'longitude', 'radius'];

    public function sekolah() { return $this->belongsTo(Sekolah::class); }
}