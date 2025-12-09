<?php

use Illuminate\Support\Facades\Route;
// Admin Pendidikan
use App\Http\Controllers\Pendidikan\DashboardController;
use App\Http\Controllers\Pendidikan\MustawaController;
use App\Http\Controllers\Pendidikan\MapelDiniyahController;
use App\Http\Controllers\Pendidikan\UstadzController;
use App\Http\Controllers\Pendidikan\RaporTemplateController;
use App\Http\Controllers\Pendidikan\KartuUjianTemplateController;
use App\Http\Controllers\Pendidikan\KartuUjianController;
use App\Http\Controllers\Pendidikan\RaporController;
use App\Http\Controllers\Pendidikan\JadwalDiniyahController;
use App\Http\Controllers\Pendidikan\AnggotaKelasController;
use App\Http\Controllers\Pendidikan\JadwalUjianController;
use App\Http\Controllers\Pendidikan\AbsensiController;
use App\Http\Controllers\Pendidikan\JurnalMonitoringController;
use App\Http\Controllers\Pendidikan\JurnalHafalanMonitoringController;
use App\Http\Controllers\Pendidikan\MonitoringNilaiUjianController;
use App\Http\Controllers\Pendidikan\RemedialController;

// Ustadz
use App\Http\Controllers\Ustadz\DashboardController as UstadzDashboardController;
use App\Http\Controllers\Ustadz\JadwalController as UstadzJadwalController;
use App\Http\Controllers\Ustadz\JurnalController;
use App\Http\Controllers\Ustadz\AbsensiController as UstadzAbsensiController;
use App\Http\Controllers\Ustadz\JurnalMengajarController;
use App\Http\Controllers\Ustadz\UjianController as UstadzUjianController;

/*
|--------------------------------------------------------------------------
| Pendidikan Pesantren (Diniyah/Madin) Routes
|--------------------------------------------------------------------------
*/

// 1. ADMIN PENDIDIKAN
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:admin-pendidikan'])
    ->prefix('pendidikan-admin')
    ->name('pendidikan.admin.')
    ->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('mustawa', MustawaController::class);
        Route::resource('mapel', MapelDiniyahController::class);
        Route::resource('ustadz', UstadzController::class);
        Route::resource('rapor-template', RaporTemplateController::class);
        
        Route::resource('kartu-template', KartuUjianTemplateController::class);
        Route::get('kartu-ujian', [KartuUjianController::class, 'index'])->name('kartu.index');
        Route::post('kartu-ujian/generate', [KartuUjianController::class, 'generate'])->name('kartu.generate');

     Route::get('kartu-ujian/get-santri/{mustawa}', [KartuUjianController::class, 'getSantriByMustawa'])
     ->name('kartu.get-santri');
        
        Route::get('rapor', [RaporController::class, 'index'])->name('rapor.index');
        Route::post('rapor/generate', [RaporController::class, 'generate'])->name('rapor.generate');

        Route::resource('jadwal', JadwalDiniyahController::class);

        Route::get('anggota-kelas', [AnggotaKelasController::class, 'index'])->name('anggota-kelas.index');
        Route::get('anggota-kelas/{mustawa}', [AnggotaKelasController::class, 'show'])->name('anggota-kelas.show');
        Route::post('anggota-kelas/{mustawa}/add', [AnggotaKelasController::class, 'store'])->name('anggota-kelas.store');
        Route::delete('anggota-kelas/{mustawa}/remove/{santri}', [AnggotaKelasController::class, 'destroy'])->name('anggota-kelas.destroy');

        Route::get('kenaikan-kelas', [AnggotaKelasController::class, 'promotionIndex'])->name('kenaikan-kelas.index');
        Route::get('kenaikan-kelas/check', [AnggotaKelasController::class, 'promotionCheck'])->name('kenaikan-kelas.check');
        Route::post('kenaikan-kelas/process', [AnggotaKelasController::class, 'promotionStore'])->name('kenaikan-kelas.store');

        Route::resource('ujian', JadwalUjianController::class)->except(['show']);
        Route::get('ujian/{ujian}/format-nilai', [JadwalUjianController::class, 'exportFormatNilai'])->name('ujian.format-nilai');
        Route::get('ujian/{ujian}/daftar-hadir', [JadwalUjianController::class, 'exportDaftarHadir'])->name('ujian.daftar-hadir');
        Route::get('ujian/{ujian}/kelola', [JadwalUjianController::class, 'show'])->name('ujian.show');
        
        Route::post('ujian/{ujian}/attendance', [JadwalUjianController::class, 'storeAttendance'])->name('ujian.attendance');
        Route::post('ujian/{ujian}/grades', [JadwalUjianController::class, 'storeGrades'])->name('ujian.grades');
        Route::get('ujian/{ujian}/pdf', [JadwalUjianController::class, 'exportPdf'])->name('ujian.pdf');
        Route::get('ujian/{ujian}/excel', [JadwalUjianController::class, 'exportExcel'])->name('ujian.excel');
        
        Route::get('absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::get('monitoring-jurnal', [JurnalMonitoringController::class, 'index'])->name('monitoring.jurnal');
        Route::get('monitoring-hafalan', [JurnalHafalanMonitoringController::class, 'index'])->name('monitoring.hafalan');

        // === TAMBAHAN BARU: MONITORING UJIAN ===
        Route::prefix('monitoring-ujian')->name('monitoring.ujian.')->group(function() {
            Route::get('/', [MonitoringNilaiUjianController::class, 'index'])->name('index');
            Route::get('/{mustawa}/mapel', [MonitoringNilaiUjianController::class, 'showMapel'])->name('mapel');
            Route::get('/{mustawa}/mapel/{mapel}', [MonitoringNilaiUjianController::class, 'showDetail'])->name('detail');
            Route::get('/{mustawa}/mapel/{mapel}/input/{jenis}', [MonitoringNilaiUjianController::class, 'showInput'])->name('input');
            Route::post('/{mustawa}/mapel/{mapel}/update/{jenis}', [MonitoringNilaiUjianController::class, 'updateNilai'])->name('update');
        });

        Route::prefix('monitoring/remedial')->name('monitoring.remedial.')->group(function () {
    Route::get('/', [RemedialController::class, 'index'])->name('index');
    Route::get('/pdf', [RemedialController::class, 'downloadPdf'])->name('pdf');
});
    });

// 2. AREA USTADZ
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:ustadz'])
    ->prefix('ustadz-area')
    ->name('ustadz.')
    ->group(function () {
        
        Route::get('/dashboard', [UstadzDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/jadwal', [UstadzJadwalController::class, 'index'])->name('jadwal.index');
        
        Route::resource('jurnal', JurnalController::class);

        Route::get('/jadwal/{jadwal}/menu', [UstadzAbsensiController::class, 'showMenu'])->name('absensi.menu');
        
        Route::get('/jadwal/{jadwal}/absensi', [UstadzAbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/jadwal/{jadwal}/absensi', [UstadzAbsensiController::class, 'store'])->name('absensi.store');

        Route::get('/jadwal/{jadwal}/jurnal-kelas', [JurnalMengajarController::class, 'create'])->name('jurnal-kelas.create');
        Route::post('/jadwal/{jadwal}/jurnal-kelas', [JurnalMengajarController::class, 'store'])->name('jurnal-kelas.store');
        
        Route::get('/jadwal/{jadwal}/riwayat', [UstadzAbsensiController::class, 'history'])->name('absensi.history');
        Route::get('/jadwal/{jadwal}/riwayat/{tanggal}', [UstadzAbsensiController::class, 'historyDetail'])->name('absensi.history.detail');
        
        Route::get('ujian', [UstadzUjianController::class, 'index'])->name('ujian.index');
        Route::get('ujian/{id}', [UstadzUjianController::class, 'show'])->name('ujian.show');
        Route::post('ujian/{id}/absensi', [UstadzUjianController::class, 'storeAbsensi'])->name('ujian.absensi');
        Route::post('ujian/{id}/nilai', [UstadzUjianController::class, 'storeNilai'])->name('ujian.nilai');
        
        Route::get('/profil', [UstadzDashboardController::class, 'profil'])->name('profil');
        Route::put('/profil', [UstadzDashboardController::class, 'updateProfil'])->name('profil.update');
    });