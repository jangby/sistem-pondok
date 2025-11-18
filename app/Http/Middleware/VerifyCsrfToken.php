<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Tambahkan baris di bawah ini:
        'midtrans/webhook',
        
        // Pastikan Anda hanya menambahkan path-nya, tanpa slash (/) di awal atau akhir
        // atau gunakan slash di awal jika ingin lebih aman (tetapi 'midtrans/webhook' sudah cukup)
    ];
}