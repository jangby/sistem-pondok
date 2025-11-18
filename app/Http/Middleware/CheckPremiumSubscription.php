<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPremiumSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // 1. Bypass jika Super Admin
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // 2. Cek user (Admin Pondok / Bendahara / Admin UJ)
        if ($user->pondokStaff && $user->pondokStaff->pondok) {

            // 3. Ambil data langganan
            $subscription = $user->pondokStaff->pondok->subscription;

            // 4. Cek apakah langganan itu "Premium"
            // (Kita cek plan->name, pastikan namanya sama persis)
            if ($subscription && $subscription->plan && $subscription->plan->name == 'Paket Premium') {
                // Jika Benar Premium, izinkan masuk
                return $next($request);
            }
        }

        // 5. Jika tidak, blokir
        // Kita bisa arahkan ke halaman 'upgrade' nanti
        abort(403, 'FITUR INI HANYA UNTUK PAKET PREMIUM.');
    }
}