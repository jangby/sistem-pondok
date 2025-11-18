<?php
namespace App\Models\Inventaris;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;

class Peminjaman extends Model {
    use BelongsToPondok;
    protected $table = 'inv_borrowings';
    protected $guarded = ['id'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'return_date' => 'date'];

    public function barang() { return $this->belongsTo(Barang::class, 'item_id'); }
}