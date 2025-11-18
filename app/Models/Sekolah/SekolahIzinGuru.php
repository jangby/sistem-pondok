<?php
namespace App\Models\Sekolah;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SekolahIzinGuru extends Model
{
    protected $table = 'sekolah_izin_guru';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'ditinjau_pada' => 'datetime',
    ];
    public function sekolah() { return $this->belongsTo(Sekolah::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_user_id'); }
    public function peninjau() { return $this->belongsTo(User::class, 'peninjau_user_id'); }
}