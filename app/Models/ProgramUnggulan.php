<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class ProgramUnggulan extends Model
{
    use BelongsToPondok;

    protected $guarded = ['id'];

    public function penanggungJawab()
    {
        return $this->belongsTo(Ustadz::class, 'penanggung_jawab_id');
    }

    public function members()
    {
        return $this->belongsToMany(Santri::class, 'program_unggulan_members')
                    ->withPivot('joined_at')
                    ->withTimestamps();
    }
}