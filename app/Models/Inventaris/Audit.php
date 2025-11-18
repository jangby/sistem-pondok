<?php
namespace App\Models\Inventaris;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Audit extends Model {
    use BelongsToPondok;
    protected $table = 'inv_audits';
    protected $guarded = ['id'];
    protected $casts = ['audit_date' => 'date'];

    public function barang() { return $this->belongsTo(Barang::class, 'item_id'); }
}