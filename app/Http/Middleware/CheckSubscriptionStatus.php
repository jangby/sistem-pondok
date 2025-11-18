<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use Carbon\Carbon;

class CheckSubscriptionStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Bypass jika Super Admin
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Hanya cek jika Admin Pondok atau Bendahara
        if ($user->hasRole(['admin-pondok', 'bendahara'])) {

            if (!$user->pondokStaff) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda tidak terhubung ke pondok.');
            }

            $pondokId = $user->pondokStaff->pondok_id;
            $subscription = Subscription::where('pondok_id', $pondokId)->first();

            // 2. Blok jika tidak ada langganan sama sekali
            if (!$subscription) {
                Auth::logout();
                return redirect()->route('langganan.berakhir')->with('error', 'Pondok Anda belum memiliki paket langganan.');
            }

            // 3. Blok jika status tidak aktif atau tanggal sudah lewat
            if ($subscription->status != 'active' || $subscription->ends_at->isPast()) {
                Auth::logout();
                return redirect()->route('langganan.berakhir')->with('error', 'Langganan pondok Anda telah berakhir.');
            }
        }

        // 4. Jika lolos, izinkan masuk
        return $next($request);
    }
}