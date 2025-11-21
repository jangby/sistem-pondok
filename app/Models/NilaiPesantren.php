<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiPesantren extends Model
{
    protected $guarded = ['id'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function mustawa()
    {
        return $this->belongsTo(Mustawa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MapelDiniyah::class, 'mapel_diniyah_id');
    }
}