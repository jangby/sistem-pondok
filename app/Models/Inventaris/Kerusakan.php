<?php
namespace App\Models\Inventaris;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Kerusakan extends Model {
    use BelongsToPondok;
    protected $table = 'inv_damages';
    protected $guarded = ['id'];
    protected $casts = ['date_reported' => 'date', 'date_resolved' => 'date'];

    public function barang() { return $this->belongsTo(Barang::class, 'item_id'); }
}