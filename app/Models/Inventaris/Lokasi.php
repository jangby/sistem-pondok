<?php
namespace App\Models\Inventaris;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Lokasi extends Model {
    use BelongsToPondok;
    protected $table = 'inv_locations';
    protected $fillable = ['pondok_id', 'name', 'description'];
    
    public function items() { return $this->hasMany(Barang::class, 'location_id'); }
}