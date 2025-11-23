<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrangTua\DashboardController;
use App\Http\Controllers\OrangTua\TagihanController;
use App\Http\Controllers\OrangTua\MidtransPaymentController;
use App\Http\Controllers\OrangTua\DompetController as OrangTuaDompetController;
use App\Http\Controllers\OrangTua\MonitoringController;

/*
|--------------------------------------------------------------------------
| Orang Tua / Wali Santri Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:orang-tua'])
     ->prefix('orangtua')
     ->name('orangtua.')
     ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/{tagihan}', [TagihanController::class, 'show'])->name('tagihan.show');
    
    // Pembayaran
    Route::get('tagihan/{tagihan}/bayar', [MidtransPaymentController::class, 'pilihMetode'])->name('tagihan.pilih-metode');
    Route::post('tagihan/{tagihan}/bayar', [MidtransPaymentController::class, 'prosesPembayaran'])->name('tagihan.proses-pembayaran');
    Route::get('pembayaran/{transaksi}/instruksi', [MidtransPaymentController::class, 'instruksiPembayaran'])->name('pembayaran.instruksi');
    Route::get('pembayaran/{transaksi}/status', [MidtransPaymentController::class, 'checkStatus'])->name('pembayaran.status');

    // Dompet
    Route::get('dompet', [OrangTuaDompetController::class, 'index'])->name('dompet.index');
    Route::get('dompet/topup', [OrangTuaDompetController::class, 'createTopup'])->name('dompet.topup.create');
    Route::post('dompet/topup', [OrangTuaDompetController::class, 'storeTopup'])->name('dompet.topup.store');
    Route::put('dompet/{dompet}', [OrangTuaDompetController::class, 'update'])->name('dompet.update');

    // Monitoring
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/{id}', [MonitoringController::class, 'index'])->name('index');
        Route::get('/{id}/kesehatan', [MonitoringController::class, 'kesehatan'])->name('kesehatan');
        Route::get('/{id}/izin', [MonitoringController::class, 'izin'])->name('izin');
        Route::get('/{id}/gerbang', [MonitoringController::class, 'gerbang'])->name('gerbang');
        Route::get('/{id}/absensi', [MonitoringController::class, 'absensi'])->name('absensi');
        Route::get('/{id}/diniyah', [MonitoringController::class, 'diniyah'])->name('diniyah');
        Route::get('/{id}/hafalan', [MonitoringController::class, 'hafalan'])->name('hafalan');
    });
});