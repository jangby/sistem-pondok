<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPondok\DashboardController as AdminPondokDashboard;
use App\Http\Controllers\AdminPondok\SantriController;
use App\Http\Controllers\AdminPondok\OrangTuaController;
use App\Http\Controllers\AdminPondok\JenisPembayaranController;
use App\Http\Controllers\AdminPondok\ItemPembayaranController;
use App\Http\Controllers\AdminPondok\KeringananController;
use App\Http\Controllers\AdminPondok\GenerateTagihanController;
use App\Http\Controllers\AdminPondok\ManualPaymentController;
use App\Http\Controllers\AdminPondok\LaporanSetoranController;
use App\Http\Controllers\AdminPondok\SelectSearchController;
use App\Http\Controllers\AdminPondok\TunggakanController;
use App\Http\Controllers\AdminPondok\SetoranController;
use App\Http\Controllers\AdminPondok\BendaharaController;
use App\Http\Controllers\AdminPondok\LaporanBulananController;
use App\Http\Controllers\AdminPondok\PembatalanController;
use App\Http\Controllers\AdminPondok\BukuBesarController;
use App\Http\Controllers\AdminPondok\PayoutController;
use App\Http\Controllers\AdminPondok\SettingController;
use App\Http\Controllers\AdminPondok\KelasController;
use App\Http\Controllers\AdminPondok\AdminUangJajanController;
use App\Http\Controllers\AdminPondok\PengurusPondokController;
use App\Http\Controllers\AdminPondok\ManajemenSekolahController;
use App\Http\Controllers\AdminPondok\TagihanController;
use App\Http\Controllers\AdminPondok\PpdbSettingController;
use App\Http\Controllers\AdminPondok\PpdbPendaftarController;

/*
|--------------------------------------------------------------------------
| Admin Pondok Routes
|--------------------------------------------------------------------------
*/

// === API INTERNAL ===
Route::middleware(['auth', 'role:admin-pondok|admin_uang_jajan', 'cek.langganan'])
     ->prefix('api-internal')
     ->name('api.')
     ->group(function () {
        Route::get('/search-santri', [SelectSearchController::class, 'searchSantri'])
             ->name('search.santri');
});

// === AREA ADMIN PONDOK ===
Route::middleware(['auth', 'role:admin-pondok', 'cek.langganan'])
     ->prefix('adminpondok')
     ->name('adminpondok.')
     ->group(function () {

    Route::get('/dashboard', [AdminPondokDashboard::class, 'index'])->name('dashboard');

    // GRUP ROUTE PPDB
    Route::prefix('ppdb')->name('ppdb.')->group(function () {
        
        // 1. Menu Pengaturan Gelombang
        Route::get('/setting', [PpdbSettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/create', [PpdbSettingController::class, 'create'])->name('setting.create');
        Route::post('/setting', [PpdbSettingController::class, 'store'])->name('setting.store');
        Route::patch('/setting/{id}/toggle', [PpdbSettingController::class, 'toggleStatus'])->name('setting.toggle');
        Route::delete('/setting/{id}', [PpdbSettingController::class, 'destroy'])->name('setting.destroy');

        // 2. Pengaturan Biaya
        Route::get('/setting/{id}/biaya', [PpdbSettingController::class, 'manageBiaya'])->name('setting.biaya');
        Route::post('/setting/{id}/biaya', [PpdbSettingController::class, 'storeBiaya'])->name('setting.biaya.store');
        Route::delete('/biaya/{id}', [PpdbSettingController::class, 'destroyBiaya'])->name('biaya.destroy');

        // 3. Manajemen Pendaftar (Verifikasi & Aksi)
        Route::get('/pendaftar', [PpdbPendaftarController::class, 'index'])->name('pendaftar.index');
        Route::get('/pendaftar/{id}', [PpdbPendaftarController::class, 'show'])->name('pendaftar.show');
        Route::post('/pendaftar/{id}/approve', [PpdbPendaftarController::class, 'approve'])->name('pendaftar.approve');
        Route::post('/pendaftar/{id}/reject', [PpdbPendaftarController::class, 'reject'])->name('pendaftar.reject');
        
        // (Opsional) Konfirmasi lunas manual lama, bisa dihapus jika sudah pakai sistem baru
        Route::post('/pendaftar/{id}/payment-confirm', [PpdbPendaftarController::class, 'confirmPayment'])->name('pendaftar.payment.confirm');
        
        Route::delete('/pendaftar/{id}', [PpdbPendaftarController::class, 'destroy'])->name('pendaftar.destroy');

        // 4. PEMBAYARAN ADMIN / KASIR (BAGIAN YANG DIPERBAIKI)
        // URL saya tambahkan '/pendaftar' agar rapi dan unik
        // Name saya tambahkan 'pendaftar.' agar menjadi 'adminpondok.ppdb.pendaftar.payment'
        Route::get('/pendaftar/{id}/payment', [PpdbPendaftarController::class, 'payment'])->name('pendaftar.payment');
        Route::post('/pendaftar/{id}/payment', [PpdbPendaftarController::class, 'storePayment'])->name('pendaftar.payment.store');
        Route::get('/pendaftar/payment/{transactionId}/print', [PpdbPendaftarController::class, 'printReceipt'])->name('pendaftar.payment.print');

        // 5. MANAJEMEN DISTRIBUSI KEUANGAN (BARU)
        Route::get('/distribusi', [App\Http\Controllers\AdminPondok\PpdbDistribusiController::class, 'index'])->name('distribusi.index');
        Route::get('/distribusi/{kategori}', [App\Http\Controllers\AdminPondok\PpdbDistribusiController::class, 'show'])->name('distribusi.show');
        Route::get('/distribusi/{kategori}/detail-item', [App\Http\Controllers\AdminPondok\PpdbDistribusiController::class, 'detailItem'])->name('distribusi.detail_item');
        Route::post('/distribusi', [App\Http\Controllers\AdminPondok\PpdbDistribusiController::class, 'store'])->name('distribusi.store');
        Route::get('/distribusi/bukti/{id}', [App\Http\Controllers\AdminPondok\PpdbDistribusiController::class, 'printBukti'])->name('distribusi.print');

        Route::resource('petugas', \App\Http\Controllers\AdminPondok\ManajemenPetugasController::class)
        ->except(['show', 'edit', 'update']);
    });

    Route::resource('santris', SantriController::class);
    Route::resource('orang-tuas', OrangTuaController::class);
    Route::resource('keringanans', KeringananController::class);

    // Rute untuk Jenis Pembayaran & Item Pembayaran
    Route::resource('jenis-pembayarans', JenisPembayaranController::class);
    Route::resource('jenis-pembayarans.items', ItemPembayaranController::class)
         ->shallow(); 

    // Rute Setoran
    Route::get('setoran', [SetoranController::class, 'index'])->name('setoran.index');       
    Route::get('setoran/create', [SetoranController::class, 'create'])->name('setoran.create'); 
    Route::post('setoran', [SetoranController::class, 'store'])->name('setoran.store');     
    Route::get('setoran/history', [SetoranController::class, 'history'])->name('setoran.history'); 
    Route::get('setoran/{setoran}', [SetoranController::class, 'show'])->name('setoran.show');
    Route::get('setoran/{setoran}/pdf', [SetoranController::class, 'downloadPDF'])->name('setoran.pdf');
    
    // Rute untuk Generate Tagihan
    Route::get('generate-tagihan', [GenerateTagihanController::class, 'create'])->name('tagihan.create');
    Route::post('generate-tagihan', [GenerateTagihanController::class, 'store'])->name('tagihan.store');

    // Rute untuk Manajemen Tagihan
    Route::resource('tagihan', TagihanController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('tagihan/{tagihan}/pay-manual', [ManualPaymentController::class, 'store'])->name('tagihan.pay-manual');
    
    Route::get('laporan-setoran', [LaporanSetoranController::class, 'index'])->name('laporan.index');
    Route::post('laporan-setoran', [LaporanSetoranController::class, 'store'])->name('laporan.store');
    Route::get('laporan-setoran/history', [LaporanSetoranController::class, 'history'])->name('laporan.history');
    Route::get('laporan-tunggakan', [TunggakanController::class, 'index'])->name('laporan.tunggakan');
    Route::post('santri/{santri}/generate-future-tagihan', [GenerateTagihanController::class, 'storeFuture'])->name('tagihan.store-future');
    
    Route::resource('bendahara', BendaharaController::class);
    Route::get('laporan-bulanan', [LaporanBulananController::class, 'index'])->name('laporan.bulanan');
    Route::get('laporan-bulanan/pdf', [LaporanBulananController::class, 'downloadPDF'])->name('laporan.bulanan.pdf');
    Route::post('transaksi/{transaksi}/cancel', [PembatalanController::class, 'cancel'])->name('transaksi.cancel');
    Route::get('buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');
    
    // Rute Payout
    Route::get('penarikan-midtrans', [PayoutController::class, 'index'])->name('payout.index');
    Route::post('penarikan-midtrans', [PayoutController::class, 'store'])->name('payout.store');
    Route::get('penarikan-midtrans/{payout}', [PayoutController::class, 'show'])->name('payout.show');
    Route::delete('penarikan-midtrans/{payout}', [PayoutController::class, 'destroy'])->name('payout.destroy');
    
    // Rute Pengaturan
    Route::get('pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [SettingController::class, 'store'])->name('pengaturan.store');

    // Rute CRUD untuk Kelas
    Route::resource('kelas', KelasController::class);

    // ========================================================
    // FITUR PREMIUM
    // ========================================================
    Route::middleware(['isPremium'])->group(function () {
        
        // --- GRUP UANG JAJAN (UUJ) ---
        Route::prefix('uuj')->name('uuj.')->group(function () {
            Route::resource('admin', AdminUangJajanController::class);
        });

        // --- GRUP MANAJEMEN KESANTRIAN (PENGURUS) ---
        Route::resource('pengurus', PengurusPondokController::class)
            ->parameters(['pengurus' => 'pengurus']); 
            
        Route::resource('manajemen-sekolah', ManajemenSekolahController::class)->parameters([
                'manajemen-sekolah' => 'user' 
            ]);
    });

});