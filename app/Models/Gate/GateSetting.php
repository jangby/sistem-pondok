<?php
namespace App\Models\Gate;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class GateSetting extends Model {
    use BelongsToPondok;
    protected $fillable = ['pondok_id', 'max_minutes_out', 'satpam_wa_number', 'auto_notify'];
}