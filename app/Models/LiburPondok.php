<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class LiburPondok extends Model {
    use BelongsToPondok;
    protected $fillable = ['pondok_id', 'tanggal', 'keterangan'];
    protected $casts = ['tanggal' => 'date'];
}