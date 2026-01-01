<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Landing\PpdbController;

Route::get('/', [PpdbController::class, 'index'])->name('welcome');

Route::get('/ppdb/daftar', [PpdbController::class, 'register'])->name('ppdb.register');
Route::post('/ppdb/daftar', [PpdbController::class, 'store'])->name('ppdb.store');

// === RUTE DASHBOARD (REDIRECTOR) ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('super-admin')) return redirect()->route('superadmin.dashboard');
    if ($user->hasRole('calon_santri')) return redirect()->route('ppdb.dashboard');
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
    if ($user->hasRole('petugas_lab')) return redirect()->route('petugas-lab.dashboard');
    if ($user->hasRole('petugas_ppdb')) return redirect()->route('petugas.dashboard');

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

// ====================================================
// RUTE KHUSUS CALON SANTRI (PPDB)
// ====================================================
Route::middleware(['auth', 'role:calon_santri'])
    ->prefix('ppdb')        // URL otomatis diawali /ppdb/
    ->name('ppdb.')         // Nama route otomatis diawali ppdb.
    ->group(function () {
    
    // Dashboard -> ppdb.dashboard
    Route::get('/dashboard', [PpdbController::class, 'dashboard'])->name('dashboard');
    
    // Biodata -> ppdb.biodata
    Route::get('/biodata', [PpdbController::class, 'biodata'])->name('biodata');
    Route::put('/biodata', [PpdbController::class, 'updateBiodata'])->name('biodata.update');

    // --- PEMBAYARAN ---
    
    // Hapus duplikasi route sebelumnya, kita pakai yang standar ini:
    
    // 1. Form Bayar -> ppdb.payment
    // URL jadi: /ppdb/payment
    Route::get('/payment', [PpdbController::class, 'payment'])->name('payment');
    
    // 2. Proses Bayar -> ppdb.payment.process
    // URL jadi: /ppdb/payment
    Route::post('/payment', [PpdbController::class, 'processPayment'])->name('payment.process');
    
    // 3. Instruksi Bayar (YANG ERROR TADI) -> ppdb.payment.instruksi
    // URL jadi: /ppdb/payment/{id}/instruksi
    // Cukup tulis 'payment.instruksi' karena prefix 'ppdb.' diambil dari grup
    Route::get('/payment/{id}/instruksi', [PpdbController::class, 'instruksi'])->name('payment.instruksi');

    // 4. Redirect Finish (Opsional jika masih dipakai)
    Route::get('/payment/finish', [PpdbController::class, 'paymentSuccess'])->name('payment.finish');
});

// === AREA PETUGAS PPDB (OFFLINE) ===
Route::middleware(['auth', 'role:petugas_ppdb'])
    ->prefix('petugas-ppdb')
    ->name('petugas.')
    ->group(function () {
        
        // Dashboard Petugas
        Route::get('/dashboard', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'dashboard'])->name('dashboard');
        
        // Pendaftaran Offline
        Route::get('/register', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'create'])->name('pendaftaran.create');
        Route::post('/register', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'store'])->name('pendaftaran.store');
        
        // Halaman Sukses (Cetak Akun)
        Route::get('/register/{id}/success', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'success'])->name('pendaftaran.success');

        // Edit & Update (BARU)
        Route::get('/register/{id}/edit', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/register/{id}', [\App\Http\Controllers\Petugas\PetugasPpdbController::class, 'update'])->name('pendaftaran.update');
});