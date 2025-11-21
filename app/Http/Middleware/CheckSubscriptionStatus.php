<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Pondok;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Bypass (Loloskan) untuk Super Admin Aplikasi
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // 2. PERBAIKAN UTAMA DI SINI: Ambil ID Pondok dengan Cerdas
        // Cek kolom 'pondok_id' di tabel users (Legacy) ATAU relasi 'pondokStaff' (New)
        $pondokId = $user->pondok_id ?? $user->pondokStaff?->pondok_id;

        // Khusus Role Orang Tua: Ambil pondok dari data anak pertamanya
        if (!$pondokId && $user->hasRole('orang-tua')) {
            $pondokId = $user->santris->first()?->pondok_id;
        }

        // Jika masih tidak ketemu pondoknya, blokir akses
        if (!$pondokId) {
             return redirect()->route('langganan.berakhir')
                 ->with('error', 'Akun tidak terhubung dengan data pondok manapun.');
        }

        // 3. Cek Status Langganan Pondok (Logika Asli Dipertahankan)
        $pondok = Pondok::find($pondokId);

        if (!$pondok) {
            return redirect()->route('langganan.berakhir');
        }

        // Asumsi logika expired Anda menggunakan kolom 'status' atau tanggal
        // Sesuaikan bagian ini jika di database Anda menggunakan nama kolom berbeda
        if ($pondok->status === 'expired' || $pondok->status === 'inactive') {
             return redirect()->route('langganan.berakhir');
        }
        
        // Jika menggunakan tanggal expired
        // if ($pondok->expired_at && now()->greaterThan($pondok->expired_at)) {
        //      return redirect()->route('langganan.berakhir');
        // }

        return $next($request);
    }
}