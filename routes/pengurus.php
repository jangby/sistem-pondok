<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pengurus\DashboardController;
use App\Http\Controllers\Pengurus\SantriController;
use App\Http\Controllers\Pengurus\UksController;
use App\Http\Controllers\Pengurus\PerizinanController;
use App\Http\Controllers\Pengurus\AbsensiController;
use App\Http\Controllers\Pengurus\Absensi\AsramaController;
use App\Http\Controllers\Pengurus\Absensi\KegiatanController;
use App\Http\Controllers\Pengurus\Absensi\JamaahController;
use App\Http\Controllers\Pengurus\Absensi\KontrolController;
use App\Http\Controllers\Pengurus\Absensi\GateController;
use App\Http\Controllers\Pengurus\ManajemenAsramaController;
use App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController;
use App\Http\Controllers\Pengurus\Inventaris\InventarisController;
use App\Http\Controllers\Pengurus\Inventaris\LokasiController;
use App\Http\Controllers\Pengurus\Inventaris\BarangController;
use App\Http\Controllers\Pengurus\Inventaris\KerusakanController;
use App\Http\Controllers\Pengurus\Inventaris\PeminjamanController;
use App\Http\Controllers\Pengurus\Inventaris\AuditController;
use App\Http\Controllers\Pengurus\PerpulanganController;

/*
|--------------------------------------------------------------------------
| Pengurus Pondok (Kesantrian) Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pengurus_pondok'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Santri
    // --- PERBAIKAN DI SINI (Hapus 'pengurus.') ---
    Route::get('/santri/export', [SantriController::class, 'export'])->name('santri.export'); 
    // Hasil akhirnya otomatis jadi: pengurus.santri.export
    
    Route::post('santri/{santri}/regenerate-qr', [SantriController::class, 'regenerateQR'])->name('santri.regenerate-qr');
    Route::get('santri/template/download', [SantriController::class, 'downloadTemplate'])->name('santri.template');
    Route::post('santri/import', [SantriController::class, 'import'])->name('santri.import');
    
    // Resource harus ditaruh SETELAH route custom (seperti export) agar tidak bentrok dengan {santri}
    Route::resource('santri', SantriController::class); 
    
    Route::post('santri/cleanup', [App\Http\Controllers\Pengurus\SantriController::class, 'cleanupFailedImport'])
        ->name('santri.cleanup');

    // UKS
    Route::get('uks', [UksController::class, 'index'])->name('uks.index');
    Route::get('uks/history', [UksController::class, 'history'])->name('uks.history');
    Route::get('uks/scan', [UksController::class, 'create'])->name('uks.scan');
    Route::post('uks/scan', [UksController::class, 'processScan'])->name('uks.process-scan'); 
    Route::post('uks/store', [UksController::class, 'store'])->name('uks.store'); 
    Route::get('uks/{uks}', [UksController::class, 'show'])->name('uks.show');
    Route::get('uks/{uks}/edit', [UksController::class, 'edit'])->name('uks.edit');
    Route::put('uks/{uks}', [UksController::class, 'update'])->name('uks.update');
    
    // Perizinan
    Route::get('perizinan/history', [PerizinanController::class, 'history'])->name('perizinan.history');
    Route::resource('perizinan', PerizinanController::class);
    Route::get('perizinan-scan', [PerizinanController::class, 'create'])->name('perizinan.scan');
    Route::post('perizinan-process', [PerizinanController::class, 'processScan'])->name('perizinan.process');

    // Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index');
        Route::get('/gerbang', [AbsensiController::class, 'gerbang'])->name('gerbang');
        Route::get('/asrama', [AbsensiController::class, 'asrama'])->name('asrama');
        Route::get('/kegiatan', [AbsensiController::class, 'kegiatan'])->name('kegiatan');
        Route::get('/jamaah', [AbsensiController::class, 'jamaah'])->name('jamaah');
        Route::get('/kontrol', [AbsensiController::class, 'kontrol'])->name('kontrol');
        
        // Asrama
        Route::get('/asrama', [AsramaController::class, 'index'])->name('asrama');
        Route::get('/asrama/rekap', [AsramaController::class, 'rekap'])->name('asrama.rekap');
        Route::get('/asrama/settings', [AsramaController::class, 'settings'])->name('asrama.settings');
        Route::post('/asrama/settings', [AsramaController::class, 'storeSettings'])->name('asrama.settings.store');
        Route::post('/asrama/libur', [AsramaController::class, 'storeLibur'])->name('asrama.libur.store');
        Route::delete('/asrama/libur/{id}', [AsramaController::class, 'deleteLibur'])->name('asrama.libur.delete');
        Route::get('/asrama/scan', [AsramaController::class, 'scan'])->name('asrama.scan');
        Route::post('/asrama/process', [AsramaController::class, 'processScan'])->name('asrama.process');
        
        // Kegiatan
        Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan');
        Route::get('/kegiatan/settings', [KegiatanController::class, 'settings'])->name('kegiatan.settings');
        Route::post('/kegiatan/settings', [KegiatanController::class, 'storeSettings'])->name('kegiatan.settings.store');
        Route::delete('/kegiatan/{id}', [KegiatanController::class, 'delete'])->name('kegiatan.delete');
        Route::get('/kegiatan/scan', [KegiatanController::class, 'scan'])->name('kegiatan.scan');
        Route::post('/kegiatan/process', [KegiatanController::class, 'processScan'])->name('kegiatan.process');
        Route::get('/kegiatan/rekap', [KegiatanController::class, 'rekapList'])->name('kegiatan.rekap');
        Route::get('/kegiatan/rekap/{id}', [KegiatanController::class, 'rekapShow'])->name('kegiatan.rekap.show');
        
        // Jamaah
        Route::get('/jamaah', [JamaahController::class, 'index'])->name('jamaah');
        Route::get('/kontrol', [KontrolController::class, 'index'])->name('kontrol');
        Route::get('/kontrol/pdf', [KontrolController::class, 'downloadPDF'])->name('kontrol.pdf');
        
        Route::get('/jamaah/haid', [JamaahController::class, 'haidIndex'])->name('jamaah.haid');
        Route::post('/jamaah/haid', [JamaahController::class, 'haidStore'])->name('jamaah.haid.store');
        Route::put('/jamaah/haid/{id}/finish', [JamaahController::class, 'haidFinish'])->name('jamaah.haid.finish');
        Route::get('/jamaah/scan', [JamaahController::class, 'scan'])->name('jamaah.scan');
        Route::post('/jamaah/process', [JamaahController::class, 'processScan'])->name('jamaah.process');

        // Gerbang
        Route::get('/gerbang', [GateController::class, 'index'])->name('gerbang');
        Route::get('/gerbang/settings', [GateController::class, 'settings'])->name('gerbang.settings');
        Route::post('/gerbang/settings', [GateController::class, 'storeSettings'])->name('gerbang.settings.store');
    });

    // Asrama Management
    Route::prefix('asrama')->name('asrama.')->group(function () {
        Route::get('/', [ManajemenAsramaController::class, 'index'])->name('index');
        Route::get('/list/{gender}', [ManajemenAsramaController::class, 'list'])->name('list');
        Route::post('/store', [ManajemenAsramaController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [ManajemenAsramaController::class, 'show'])->name('show');
        Route::get('/settings/{id}', [ManajemenAsramaController::class, 'settings'])->name('settings'); 
        Route::put('/update/{id}', [ManajemenAsramaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ManajemenAsramaController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/add-member', [ManajemenAsramaController::class, 'addMember'])->name('member.add');
        Route::delete('/member/{santriId}/remove', [ManajemenAsramaController::class, 'removeMember'])->name('member.remove');
        Route::get('/pdf-data', [ManajemenAsramaController::class, 'downloadPDF'])->name('pdf.data');

        // Absensi Ketua Asrama
        Route::prefix('ketua')->name('ketua.')->group(function() {
            Route::get('/', [AbsensiKetuaController::class, 'index'])->name('index'); 
            Route::post('/process', [AbsensiKetuaController::class, 'process'])->name('process');
            Route::get('/settings', [AbsensiKetuaController::class, 'settings'])->name('settings');
            Route::post('/settings', [AbsensiKetuaController::class, 'storeSettings'])->name('settings.store');
            Route::get('/rekap', [AbsensiKetuaController::class, 'rekap'])->name('rekap');
            Route::get('/rekap/{id}', [AbsensiKetuaController::class, 'rekapDetail'])->name('rekap.detail');
        });
    });

    // Inventaris
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', [InventarisController::class, 'index'])->name('index');
        Route::resource('lokasi', LokasiController::class);
        Route::get('barang/print-labels/{id}', [App\Http\Controllers\Pengurus\Inventaris\BarangController::class, 'printLabels'])
        ->name('barang.print-labels');
        Route::resource('barang', App\Http\Controllers\Pengurus\Inventaris\BarangController::class);
        Route::resource('barang', BarangController::class);
        Route::get('barang/lokasi/{id}', [BarangController::class, 'byLokasi'])->name('barang.by_lokasi');
        Route::resource('kerusakan', KerusakanController::class);
        Route::post('kerusakan/{id}/resolve', [KerusakanController::class, 'resolve'])->name('kerusakan.resolve'); 
        Route::resource('peminjaman', PeminjamanController::class);
        Route::post('peminjaman/{id}/return', [PeminjamanController::class, 'kembali'])->name('peminjaman.return');
        Route::get('rekap', [AuditController::class, 'index'])->name('rekap.index');
        Route::get('rekap/scan', [AuditController::class, 'scan'])->name('rekap.scan'); 
        Route::post('rekap/process', [AuditController::class, 'process'])->name('rekap.process'); 
        Route::post('rekap/{id}/reconcile', [AuditController::class, 'reconcile'])->name('rekap.reconcile'); 
        Route::get('rekap/pdf', [AuditController::class, 'downloadPDF'])->name('rekap.pdf');
    });

    Route::prefix('perpulangan')->name('perpulangan.')->group(function () {
    // Menu Utama & CRUD Event
    Route::get('/', [PerpulanganController::class, 'index'])->name('index');
    Route::get('/create', [PerpulanganController::class, 'create'])->name('create');
    Route::post('/store', [PerpulanganController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PerpulanganController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PerpulanganController::class, 'update'])->name('update');
    Route::delete('/{id}', [PerpulanganController::class, 'destroy'])->name('destroy');

    // Menu Cetak Kartu (Tahap berikutnya)
    Route::get('/{id}/pilih-santri', [PerpulanganController::class, 'pilihSantri'])->name('pilih-santri');
    Route::post('/{id}/cetak', [PerpulanganController::class, 'cetakKartu'])->name('cetak');

    // Halaman Scan
    Route::get('/scan', [PerpulanganController::class, 'scanIndex'])->name('scan');
    // Proses Ajax
    Route::post('/scan/process', [PerpulanganController::class, 'scanProcess'])->name('scan.process');
    Route::get('/{id}', [PerpulanganController::class, 'show'])->name('show');
});
});