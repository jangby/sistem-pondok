<?php
namespace App\Models\Inventaris;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPondok;
use App\Models\Santri;

class Barang extends Model {
    use BelongsToPondok;
    protected $table = 'inv_items';
    protected $guarded = ['id']; // Semua kolom bisa diisi kecuali ID

    // Helper: Total Aset Fisik (Bagus + Rusak + Sedang Perbaikan + Dipinjam)
    // Barang hilang/musnah tidak dihitung sebagai aset fisik yang ada
    public function getTotalPhysicalAttribute() {
        return $this->qty_good + $this->qty_damaged + $this->qty_repairing + $this->qty_borrowed;
    }
    
    public function location() { return $this->belongsTo(Lokasi::class, 'location_id'); }
    public function pic() { return $this->belongsTo(Santri::class, 'pic_santri_id'); }
    public function damages() { return $this->hasMany(Kerusakan::class, 'item_id'); }
}