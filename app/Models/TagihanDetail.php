<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'tagihan_id', 'nama_item', 'prioritas',
        'nominal_item', 'sisa_tagihan_item', 'status_item',
    ];

    // ... (relasi tagihan) ...
    public function tagihan() { return $this->belongsTo(Tagihan::class); }
}