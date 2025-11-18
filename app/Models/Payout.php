<?php
namespace App\Models;

use App\Traits\BelongsToPondok; // Gunakan Trait kita
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Payout extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'pondok_id', 'admin_id_request', 'superadmin_id_approve',
        'total_amount', 'status', 'catatan_admin', 'catatan_superadmin',
        'bukti_transfer_url', 'requested_at', 'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relasi ke pondok yg minta
    public function pondok() { return $this->belongsTo(Pondok::class); }

    // Relasi ke admin yg minta
    public function adminRequest() { return $this->belongsTo(User::class, 'admin_id_request'); }

    // Relasi ke transaksi yg ditarik
    public function transaksis()
    {
        return $this->hasMany(PembayaranTransaksi::class);
    }

    /**
     * Relasi ke Super Admin yang menyetujui.
     */
    public function superadminApprove()
    {
        return $this->belongsTo(User::class, 'superadmin_id_approve');
    }
}