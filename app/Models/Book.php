<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Book extends Model
{
    // Agar semua kolom bisa diisi (kecuali id)
    protected $guarded = ['id'];

    // Relasi: Buku milik Pondok
    public function pondok(): BelongsTo
    {
        return $this->belongsTo(Pondok::class);
    }

    // Relasi: Buku punya banyak Bab
    // Kita urutkan otomatis berdasarkan 'sequence' (urutan) biar rapi
    public function chapters(): HasMany
    {
        return $this->hasMany(BookChapter::class)->orderBy('sequence', 'asc');
    }

    // Relasi Pintas: Mengambil semua item doa dalam satu buku (melewati bab)
    // Berguna jika ingin hitung total doa atau cari doa tertentu di buku ini
    public function allItems(): HasManyThrough
    {
        return $this->hasManyThrough(BookItem::class, BookChapter::class, 'book_id', 'chapter_id');
    }
}