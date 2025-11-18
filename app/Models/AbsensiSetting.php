<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class AbsensiSetting extends Model {
    use BelongsToPondok;
    protected $fillable = ['pondok_id', 'jenis', 'jam_mulai', 'jam_selesai'];
}