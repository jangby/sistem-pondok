<?php
namespace App\Models;
use App\Traits\BelongsToPondok;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory, BelongsToPondok;

    protected $fillable = [
        'santri_id', 'jenis_pembayaran_id', 'invoice_number',
        'nominal_asli', 'nominal_keringanan', 'nominal_tagihan',
        'periode_bulan', 'periode_tahun', 'due_date', 'status', 'keterangan', 'pondok_id',
    ];

public function santri()
{
    return $this->belongsTo(Santri::class);
}

// Relasi ke pondok pemilik tagihan
public function pondok()
{
    return $this->belongsTo(Pondok::class);
}

// Relasi ke rincian item tagihannya
public function tagihanDetails()
{
    return $this->hasMany(TagihanDetail::class);
}

public function jenisPembayaran() { return $this->belongsTo(JenisPembayaran::class); }

/**
     * Relasi ke transaksi-transaksi pembayaran yang dimiliki tagihan ini.
     * INI ADALAH FUNGSI YANG HILANG.
     */
    public function transaksis()
    {
        return $this->hasMany(PembayaranTransaksi::class);
    }
}

