<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\SetoranController as BendaharaSetoranController;
use App\Http\Controllers\Bendahara\KasController;
use App\Http\Controllers\Bendahara\TunggakanController as BendaharaTunggakanController;

/*
|--------------------------------------------------------------------------
| Bendahara Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:bendahara', 'cek.langganan'])
     ->prefix('bendahara')
     ->name('bendahara.')
     ->group(function () {
    
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');
    
    // Setoran
    Route::get('/setoran-masuk', [BendaharaSetoranController::class, 'index'])->name('setoran.index');
    Route::get('/setoran/{setoran}', [BendaharaSetoranController::class, 'show'])->name('setoran.show');
    Route::post('/setoran/{setoran}/konfirmasi', [BendaharaSetoranController::class, 'konfirmasi'])->name('setoran.konfirmasi');
    Route::get('/setoran/{setoran}/pdf', [BendaharaSetoranController::class, 'downloadPDF'])->name('setoran.pdf');
    
    // Kas
    Route::get('kas/pdf', [KasController::class, 'downloadPDF'])->name('kas.pdf');
    Route::resource('kas', KasController::class)->except(['show']);
     
    // Tunggakan
    Route::get('/tunggakan', [BendaharaTunggakanController::class, 'index'])->name('tunggakan.index');
    Route::get('/tunggakan/{santri}', [BendaharaTunggakanController::class, 'show'])->name('tunggakan.show');
});