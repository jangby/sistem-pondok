<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UangJajan\DashboardController as UjDashboardController;
use App\Http\Controllers\UangJajan\WarungController;
use App\Http\Controllers\UangJajan\DompetController;
use App\Http\Controllers\UangJajan\TransaksiManualController;
use App\Http\Controllers\UangJajan\WarungPayoutController;
use App\Http\Controllers\UangJajan\UujPayoutController;
use App\Http\Controllers\Pos\PosController;

/*
|--------------------------------------------------------------------------
| Uang Jajan & POS Routes
|--------------------------------------------------------------------------
*/

// === AREA ADMIN UANG JAJAN ===
Route::middleware(['auth', 'role:admin_uang_jajan', 'cek.langganan', 'isPremium'])
     ->prefix('uuj-admin') 
     ->name('uuj-admin.')
     ->group(function () {
    
    Route::get('/dashboard', [UjDashboardController::class, 'index'])->name('dashboard');
    Route::resource('warung', WarungController::class);

    // Dompet & Transaksi
    Route::get('dompet', [DompetController::class, 'index'])->name('dompet.index');
    Route::get('dompet/{santri}/activate', [DompetController::class, 'activate'])->name('dompet.activate'); 
    Route::post('dompet/{santri}', [DompetController::class, 'store'])->name('dompet.store'); 
    Route::get('dompet/{dompet}/edit', [DompetController::class, 'edit'])->name('dompet.edit'); 
    Route::put('dompet/{dompet}', [DompetController::class, 'update'])->name('dompet.update');
    
    Route::get('topup', [TransaksiManualController::class, 'createTopup'])->name('topup.create');
    Route::post('topup', [TransaksiManualController::class, 'storeTopup'])->name('topup.store');

    Route::get('tarik-tunai', [TransaksiManualController::class, 'createTarik'])->name('tarik.create');
    Route::post('tarik-tunai', [TransaksiManualController::class, 'storeTarik'])->name('tarik.store');
    Route::post('tarik-tunai/find-santri', [TransaksiManualController::class, 'findSantri'])->name('tarik.find-santri');
    
    // Payout Warung
    Route::get('payout-warung', [WarungPayoutController::class, 'index'])->name('payout.index');
    Route::post('payout-warung/{id}/approve', [WarungPayoutController::class, 'approve'])->name('payout.approve');
    Route::post('payout-warung/{id}/reject', [WarungPayoutController::class, 'reject'])->name('payout.reject');
    
    Route::get('pencairan-dana', [UujPayoutController::class, 'index'])->name('pencairan.index');
    Route::post('pencairan-dana', [UujPayoutController::class, 'store'])->name('pencairan.store');
});

// === AREA KASIR WARUNG / POS ===
Route::middleware(['auth', 'role:pos_warung', 'cek.langganan', 'isPremium'])
     ->prefix('pos')
     ->name('pos.')
     ->group(function () {

    Route::get('/dashboard', [PosController::class, 'dashboard'])->name('dashboard');
    Route::get('/kasir', [PosController::class, 'index'])->name('index'); 

    Route::get('/riwayat', [PosController::class, 'history'])->name('history');
    Route::get('/penarikan', [PosController::class, 'payout'])->name('payout');
    Route::post('/penarikan', [PosController::class, 'storePayout'])->name('payout.store');
    Route::get('/penarikan/{id}', [PosController::class, 'showPayout'])->name('payout.show');

    Route::post('/find-santri', [PosController::class, 'findSantri'])->name('find-santri');
    Route::post('/process-transaction', [PosController::class, 'processTransaction'])->name('process-transaction');
});