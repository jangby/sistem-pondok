<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Haid extends Model
{
    use BelongsToPondok;
    
    protected $fillable = ['pondok_id', 'santri_id', 'tgl_mulai', 'tgl_selesai', 'catatan'];
    protected $casts = ['tgl_mulai' => 'date', 'tgl_selesai' => 'date'];

    public function santri() {
        return $this->belongsTo(Santri::class);
    }
}