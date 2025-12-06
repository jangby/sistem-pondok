<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransWebhookController;

Route::get('/', function () {
    return view('welcome');
});

// === RUTE DASHBOARD (REDIRECTOR) ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('super-admin')) return redirect()->route('superadmin.dashboard');
    if ($user->hasRole('admin-pondok')) return redirect()->route('adminpondok.dashboard');
    if ($user->hasRole('super-admin-sekolah')) return redirect()->route('sekolah.superadmin.dashboard');
    if ($user->hasRole('admin-sekolah')) return redirect()->route('sekolah.admin.dashboard');
    if ($user->hasRole('guru')) return redirect()->route('sekolah.guru.dashboard');
    if ($user->hasRole('pengurus_pondok')) return redirect()->route('pengurus.dashboard');
    if ($user->hasRole('admin_uang_jajan')) return redirect()->route('uuj-admin.dashboard');
    if ($user->hasRole('pos_warung')) return redirect()->route('pos.index');
    if ($user->hasRole('bendahara')) return redirect()->route('bendahara.dashboard');
    if ($user->hasRole('orang-tua')) return redirect()->route('orangtua.dashboard');
    if ($user->hasRole('admin-pendidikan')) return redirect()->route('pendidikan.admin.dashboard');
    if ($user->hasRole('ustadz')) return redirect()->route('ustadz.dashboard');
    if ($user->hasRole('petugas_perpus')) return redirect()->route('sekolah.petugas.dashboard');
    //if ($user->hasRole('petugas_lab')) return redirect()->route('sekolah.petugas.lab-komputer.dashboard');

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === WEBHOOK HANDLER ===
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

Route::get('/langganan-berakhir', function (Illuminate\Http\Request $request) {
    $error = $request->session()->get('error', 'Langganan Anda telah berakhir.');
    return view('auth.langganan-berakhir', ['error' => $error]);
})->name('langganan.berakhir');

require __DIR__.'/auth.php';

// Rute spesifik yang bypass CSRF sebaiknya tetap di define atau pastikan middleware exclude-nya sudah benar
Route::post('/gerbang/api/scan', [App\Http\Controllers\Pengurus\Absensi\GateController::class, 'apiScan'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]) 
    ->name('gerbang.api.scan');