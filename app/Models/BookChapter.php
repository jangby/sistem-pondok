<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookChapter extends Model
{
    protected $guarded = ['id'];

    // Relasi: Bab milik satu Buku
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi: Bab punya banyak Item Doa
    // Diurutkan juga berdasarkan sequence
    public function items(): HasMany
    {
        return $this->hasMany(BookItem::class, 'chapter_id')->orderBy('sequence', 'asc');
    }
}