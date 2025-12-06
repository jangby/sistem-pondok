<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PondokController;
use App\Http\Controllers\SuperAdmin\AdminPondokController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\MidtransReportController;
use App\Http\Controllers\SuperAdmin\PayoutController as SuperAdminPayoutController;
use App\Http\Controllers\SuperAdmin\UujPayoutController;
use App\Http\Controllers\SuperAdmin\ComputerManagerController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:super-admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('pondoks', PondokController::class);
        Route::get('admins/create', [AdminPondokController::class, 'create'])->name('admins.create');
        Route::post('admins', [AdminPondokController::class, 'store'])->name('admins.store');
        Route::resource('plans', PlanController::class);
        Route::post('pondoks/{pondok}/subscribe', [SubscriptionController::class, 'store'])->name('pondoks.subscribe');

        Route::get('midtrans-report', [MidtransReportController::class, 'index'])->name('midtrans.report');
        Route::get('payouts', [SuperAdminPayoutController::class, 'index'])->name('payouts.index');
        Route::get('payouts/{payout}', [SuperAdminPayoutController::class, 'show'])->name('payouts.show');
        Route::post('payouts/{payout}/confirm', [SuperAdminPayoutController::class, 'confirm'])->name('payouts.confirm');

        Route::get('uuj-payouts', [UujPayoutController::class, 'index'])->name('uuj-payout.index');
        Route::put('uuj-payouts/{id}', [UujPayoutController::class, 'update'])->name('uuj-payout.update');

        Route::get('/computer-manager', [ComputerManagerController::class, 'index'])
    ->name('computer.index'); // <-- Cukup begini
        Route::post('/computer-manager/{id}/command', [ComputerManagerController::class, 'sendCommand'])
    ->name('computer.command');
    });