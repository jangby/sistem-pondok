<?php

namespace App\Traits;

use App\Models\Pondok;
use Illuminate\Support\Facades\Auth;

trait BelongsToPondok
{
    /**
     * Boot trait ini untuk model.
     */
    protected static function bootBelongsToPondok()
    {
        // 1. OTOMATIS FILTER DATA (Saat 'SELECT')
        // Terapkan Global Scope untuk memfilter data berdasarkan pondok_id
        static::addGlobalScope('pondok', function ($builder) {
            if (Auth::check() && Auth::user()->hasRole('admin-pondok')) {
                $pondokId = Auth::user()->pondokStaff->pondok_id;
                $builder->where($builder->getModel()->getTable() . '.pondok_id', $pondokId);
            }
        });

        // 2. OTOMATIS ISI DATA (Saat 'INSERT')
        // Saat membuat data baru, otomatis isi pondok_id
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->hasRole('admin-pondok')) {
                if (is_null($model->pondok_id)) {
                    $model->pondok_id = Auth::user()->pondokStaff->pondok_id;
                }
            }
        });
    }

    /**
     * Relasi bahwa model ini "milik" satu Pondok.
     */
    public function pondok()
    {
        return $this->belongsTo(Pondok::class);
    }
}