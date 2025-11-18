<?php
namespace App\Models;
use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Model;
class PondokSetting extends Model
{
    use BelongsToPondok;
    protected $table = 'pondok_settings';
    protected $fillable = ['pondok_id', 'nama_resmi', 'alamat', 'telepon', 'logo_url'];
}