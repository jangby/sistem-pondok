<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbBiaya extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
    'ppdb_setting_id',
    'nama_biaya',
    'nominal',
    'kategori', // <--- TAMBAHKAN INI
    'jenjang',
    'gelombang',
    'keterangan',
];

    public function ppdbSetting()
    {
        return $this->belongsTo(PpdbSetting::class);
    }
}