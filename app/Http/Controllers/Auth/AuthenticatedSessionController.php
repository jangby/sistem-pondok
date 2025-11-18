<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- LOGIKA REDIRECT BERDASARKAN ROLE (PERBAIKAN) ---
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return redirect()->route('superadmin.dashboard');
        } 
        
        elseif ($user->hasRole('admin-pondok')) {
            return redirect()->route('adminpondok.dashboard');
        } 
        
        elseif ($user->hasRole('pengurus_pondok')) {
            // INI YANG PENTING: Arahkan Pengurus ke Dashboard Mobile
            return redirect()->route('pengurus.dashboard');
        }
        
        elseif ($user->hasRole('orang-tua')) {
            return redirect()->route('orangtua.dashboard');
        }
        
        elseif ($user->hasRole('bendahara')) {
            return redirect()->route('bendahara.dashboard');
        }
        
        elseif ($user->hasRole('pos_warung')) {
            return redirect()->route('pos.dashboard');
        }
        
        elseif ($user->hasRole('admin_uang_jajan')) {
            return redirect()->route('uuj-admin.dashboard');
        }

        // Default jika tidak punya role khusus
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}