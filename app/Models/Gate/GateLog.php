<?php
namespace App\Models\Gate;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\Santri;

class GateLog extends Model {
    use BelongsToPondok;
    protected $fillable = ['pondok_id', 'santri_id', 'out_time', 'in_time', 'is_late', 'notified'];
    protected $casts = ['out_time' => 'datetime', 'in_time' => 'datetime'];

    public function santri() {
        return $this->belongsTo(Santri::class);
    }
}