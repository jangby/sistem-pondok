<?php

use Illuminate\Support\Facades\Route;
// Super Admin Sekolah
use App\Http\Controllers\Sekolah\SuperAdmin\DashboardController as SuperAdminSekolahDashboard;
use App\Http\Controllers\Sekolah\SuperAdmin\SekolahController as SuperAdminSekolahController;
use App\Http\Controllers\Sekolah\SuperAdmin\AdminSekolahController as SuperAdminAdminSekolahController;
use App\Http\Controllers\Pendidikan\RaporTemplateController;
use App\Http\Controllers\Sekolah\Admin\PersetujuanIzinController;
use App\Http\Controllers\Sekolah\SuperAdmin\GuruController as SuperAdminGuruController;
use App\Http\Controllers\Sekolah\SuperAdmin\TahunAjaranController as SuperAdminTahunAjaranController;
use App\Http\Controllers\Sekolah\SuperAdmin\AdminPendidikanController;

// Admin Sekolah
use App\Http\Controllers\Sekolah\Admin\DashboardController as AdminSekolahDashboard;
use App\Http\Controllers\Sekolah\Admin\MataPelajaranController;
use App\Http\Controllers\Sekolah\Admin\JadwalPelajaranController;
use App\Http\Controllers\Sekolah\Admin\KegiatanAkademikController;
use App\Http\Controllers\Sekolah\Admin\LaporanNilaiController;
use App\Http\Controllers\Sekolah\Admin\KelasController;
use App\Http\Controllers\Sekolah\Admin\GuruPenggantiController;
use App\Http\Controllers\Sekolah\Admin\KinerjaGuruController;
use App\Http\Controllers\Sekolah\Admin\KinerjaSiswaController;
use App\Http\Controllers\Sekolah\Admin\LaporanController;
use App\Http\Controllers\Sekolah\Admin\KonfigurasiController;
use App\Http\Controllers\Sekolah\Admin\AbsensiMonitoringController;

// Guru
use App\Http\Controllers\Sekolah\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Sekolah\Guru\AbsensiKehadiranController;
use App\Http\Controllers\Sekolah\Guru\JadwalMengajarController;
use App\Http\Controllers\Sekolah\Guru\AbsensiSiswaController;
use App\Http\Controllers\Sekolah\Guru\NilaiController;
use App\Http\Controllers\Sekolah\Guru\IzinController;

// Petugas Perpus (Controller Baru)
use App\Http\Controllers\Sekolah\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Sekolah\Petugas\SirkulasiController as PetugasSirkulasiController;
use App\Http\Controllers\Sekolah\Petugas\BukuController as PetugasBukuController;
use App\Http\Controllers\Sekolah\Petugas\KunjunganController as PetugasKunjunganController;
use App\Http\Controllers\Sekolah\Petugas\AuditController as PetugasAuditController;

use App\Http\Controllers\Sekolah\Petugas\ComputerLabController;

/*
|--------------------------------------------------------------------------
| Sekolah Formal Routes (Premium)
|--------------------------------------------------------------------------
*/

// 1. SUPER ADMIN SEKOLAH
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:super-admin-sekolah'])
    ->prefix('sekolah-superadmin')
    ->name('sekolah.superadmin.')
    ->group(function () {
        
        Route::get('/dashboard', [SuperAdminSekolahDashboard::class, 'index'])->name('dashboard');
        Route::resource('sekolah', SuperAdminSekolahController::class);
        Route::resource('admin-sekolah', SuperAdminAdminSekolahController::class)->parameters(['admin-sekolah' => 'user']);
        Route::resource('rapor-template', RaporTemplateController::class);
        Route::get('persetujuan-izin', [PersetujuanIzinController::class, 'index'])->name('persetujuan-izin.index');
        Route::post('persetujuan-izin/{sekolahIzinGuru}/approve', [PersetujuanIzinController::class, 'approve'])->name('persetujuan-izin.approve');
        Route::post('persetujuan-izin/{sekolahIzinGuru}/reject', [PersetujuanIzinController::class, 'reject'])->name('persetujuan-izin.reject');
        Route::resource('guru', SuperAdminGuruController::class)->parameters(['guru' => 'user']);
        Route::resource('tahun-ajaran', SuperAdminTahunAjaranController::class)->except(['show']);
        Route::resource('admin-pendidikan', AdminPendidikanController::class);
        Route::post('tahun-ajaran/{tahunAjaran}/activate', [SuperAdminTahunAjaranController::class, 'activate'])->name('tahun-ajaran.activate');

        /*
        |--------------------------------------------------------------------------
        | Modul Perpustakaan (Super Admin)
        |--------------------------------------------------------------------------
        */
        Route::prefix('perpustakaan')->name('perpustakaan.')->group(function () {
            Route::resource('buku', \App\Http\Controllers\Sekolah\SuperAdmin\Perpus\BukuController::class);
            Route::resource('petugas', \App\Http\Controllers\Sekolah\SuperAdmin\Perpus\PetugasController::class); // <--- CRUD Petugas
            
            Route::get('kunjungan', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\KunjunganController::class, 'index'])->name('kunjungan.index');
            Route::get('kunjungan/kiosk', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\KunjunganController::class, 'kiosk'])->name('kunjungan.kiosk');
            Route::post('kunjungan', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\KunjunganController::class, 'store'])->name('kunjungan.store');
            
            Route::get('anggota/kartu', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\AnggotaController::class, 'cetakKartu'])->name('anggota.kartu');

            Route::prefix('sirkulasi')->name('sirkulasi.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'index'])->name('index');
                Route::get('create', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'create'])->name('create');
                Route::post('store', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'store'])->name('store');
                
                Route::get('kembali', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'returnIndex'])->name('kembali.index');
                Route::get('kembali/{peminjaman}', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'returnForm'])->name('kembali.form');
                Route::post('kembali/{peminjaman}', [\App\Http\Controllers\Sekolah\SuperAdmin\Perpus\SirkulasiController::class, 'returnProcess'])->name('kembali.process');
            });
        });
    });

// 2. ADMIN SEKOLAH
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:admin-sekolah'])
    ->prefix('sekolah-admin')
    ->name('sekolah.admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminSekolahDashboard::class, 'index'])->name('dashboard');
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);
        Route::resource('kegiatan-akademik', KegiatanAkademikController::class)->except(['show']);

        Route::prefix('kegiatan-akademik/{kegiatan}')->name('kegiatan-akademik.')->group(function () {
            Route::get('kelola', [LaporanNilaiController::class, 'showKelas'])->name('kelola.kelas');
            Route::get('kelas/{kelas}/mapel', [LaporanNilaiController::class, 'showMapel'])->name('kelola.mapel');
            Route::get('kelas/{kelas}/mapel/{mapel}/nilai', [LaporanNilaiController::class, 'showNilaiTable'])->name('kelola.nilai');
            Route::get('pdf/kelas/{kelas}/mapel/{mapel}', [LaporanNilaiController::class, 'cetakLedgerNilai'])->name('cetak.nilai');
            Route::get('pdf/kelas/{kelas}/mapel/{mapel}/format', [LaporanNilaiController::class, 'cetakFormatNilai'])->name('cetak.format-nilai');
            Route::get('pdf/kelas/{kelas}/mapel/{mapel}/hadir', [LaporanNilaiController::class, 'cetakDaftarHadir'])->name('cetak.daftar-hadir');
        });

        Route::resource('kelas', KelasController::class);
        Route::post('kelas/{kela}/add-santri', [KelasController::class, 'addSantri'])->name('kelas.addSantri');
        Route::post('santri/{santri}/move-santri', [KelasController::class, 'moveSantri'])->name('kelas.moveSantri');
        Route::get('naik-kelas', [KelasController::class, 'naikKelasView'])->name('kelas.naikKelas.view');
        Route::post('naik-kelas', [KelasController::class, 'naikKelasProcess'])->name('kelas.naikKelas.process');

        Route::get('guru-pengganti', [GuruPenggantiController::class, 'index'])->name('guru-pengganti.index');
        Route::post('guru-pengganti', [GuruPenggantiController::class, 'store'])->name('guru-pengganti.store');

        Route::get('kinerja/guru', [KinerjaGuruController::class, 'index'])->name('kinerja.guru');
        Route::get('kinerja/siswa', [KinerjaSiswaController::class, 'index'])->name('kinerja.siswa');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
        
        Route::prefix('konfigurasi')->name('konfigurasi.')->group(function () {
            Route::get('/', [KonfigurasiController::class, 'index'])->name('index');
            Route::post('settings', [KonfigurasiController::class, 'storeSettings'])->name('settings.store');
            Route::post('hari-libur', [KonfigurasiController::class, 'storeHariLibur'])->name('hari-libur.store');
            Route::delete('hari-libur/{sekolahHariLibur}', [KonfigurasiController::class, 'destroyHariLibur'])->name('hari-libur.destroy');
            Route::post('wifi', [KonfigurasiController::class, 'storeWifi'])->name('wifi.store');
            Route::delete('wifi/{sekolahWifi}', [KonfigurasiController::class, 'destroyWifi'])->name('wifi.destroy');
            Route::post('geofence', [KonfigurasiController::class, 'storeGeofence'])->name('geofence.store');
            Route::delete('geofence/{sekolahLokasiGeofence}', [KonfigurasiController::class, 'destroyGeofence'])->name('geofence.destroy');
            Route::get('kios-kode', [KonfigurasiController::class, 'showKiosKode'])->name('kios.show');
            Route::get('kios-kode/new', [KonfigurasiController::class, 'getNewKodeAbsen'])->name('kios.new_kode');
        });

        Route::prefix('monitoring-absensi')->name('monitoring.')->group(function () {
            Route::get('guru', [AbsensiMonitoringController::class, 'rekapGuru'])->name('guru');
            Route::get('siswa', [AbsensiMonitoringController::class, 'rekapSiswa'])->name('siswa');
        });
    });

// 3. GURU
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:guru'])
    ->prefix('sekolah-guru')
    ->name('sekolah.guru.')
    ->group(function () {
        
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
        Route::get('absensi-kehadiran', [AbsensiKehadiranController::class, 'index'])->name('absensi.kehadiran.index');
        Route::post('absensi-kehadiran', [AbsensiKehadiranController::class, 'store'])->name('absensi.kehadiran.store');

        Route::get('jadwal-mengajar', [JadwalMengajarController::class, 'index'])->name('jadwal.index');
        Route::get('jadwal-mengajar/{jadwal_pelajaran}', [JadwalMengajarController::class, 'show'])->name('jadwal.show');
        Route::post('jadwal-mengajar/absen-masuk', [JadwalMengajarController::class, 'storeAbsenMengajar'])->name('jadwal.absen');
        Route::post('jadwal-mengajar/simpan-materi/{absensi_pelajaran}', [JadwalMengajarController::class, 'storeMateri'])->name('jadwal.absen.materi');

        Route::get('absensi-siswa/{absensi_pelajaran}', [AbsensiSiswaController::class, 'index'])->name('absensi.siswa.index');
        Route::post('absensi-siswa', [AbsensiSiswaController::class, 'store'])->name('absensi.siswa.store');

        Route::get('input-nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('input-nilai/{kegiatan}/kelas', [NilaiController::class, 'showKelas'])->name('nilai.kelas');
        Route::get('input-nilai/{kegiatan}/kelas/{kelasId}/mapel/{mapelId}', [NilaiController::class, 'formNilai'])->name('nilai.form');
        Route::post('input-nilai/{kegiatan}/store', [NilaiController::class, 'store'])->name('nilai.store');
        Route::get('izin', [IzinController::class, 'index'])->name('izin.index');
        Route::get('izin/create', [IzinController::class, 'create'])->name('izin.create');
        Route::post('izin', [IzinController::class, 'store'])->name('izin.store');
    });

// 4. PETUGAS PERPUSTAKAAN (MOBILE FRIENDLY)
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:petugas_perpus'])
    ->prefix('sekolah-petugas-perpus')
    ->name('sekolah.petugas.')
    ->group(function () {
        
        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

        // Menu Sirkulasi
        Route::prefix('sirkulasi')->name('sirkulasi.')->group(function () {
            Route::get('/', [PetugasSirkulasiController::class, 'index'])->name('index');
            Route::post('/', [PetugasSirkulasiController::class, 'store'])->name('store');
            
            // INI RUTE YANG TADINYA HILANG (KEMBALI)
            Route::get('kembali/{peminjaman}', [PetugasSirkulasiController::class, 'returnForm'])->name('kembali.form');
            Route::post('kembali/{peminjaman}', [PetugasSirkulasiController::class, 'returnProcess'])->name('kembali.process');
        });

        // Menu Buku
        Route::get('/buku', [PetugasBukuController::class, 'index'])->name('buku.index');
        
        // Menu Kunjungan
        Route::get('/kunjungan', [PetugasKunjunganController::class, 'index'])->name('kunjungan.index');
        Route::post('/kunjungan', [PetugasKunjunganController::class, 'store'])->name('kunjungan.store');

        // Menu Audit
        Route::get('/audit', [PetugasAuditController::class, 'index'])->name('audit.index');
        Route::post('/audit/update', [PetugasAuditController::class, 'updateStock'])->name('audit.update');
    });

    Route::middleware(['auth', 'role:petugas_lab'])->prefix('petugas-lab')->name('petugas-lab.')->group(function () {
    
    Route::get('/dashboard', [ComputerLabController::class, 'dashboard'])->name('dashboard');
    
    // Menu 1: Data PC
    Route::get('/komputer', [ComputerLabController::class, 'listKomputer'])->name('komputer.index');
    
    // Menu 2: Shutdown All
    Route::post('/shutdown-all', [ComputerLabController::class, 'shutdownAll'])->name('shutdown.all');
    
    // Menu 3: Refresh
    Route::get('/refresh', [ComputerLabController::class, 'refreshStatus'])->name('refresh');
    
    // Menu 4: Ganti Password
    Route::get('/ganti-password', [ComputerLabController::class, 'massPasswordForm'])->name('password.form');
    Route::post('/ganti-password', [ComputerLabController::class, 'massPasswordUpdate'])->name('password.update');
    
    // Menu 5: Jadwal
    Route::get('/jadwal', [ComputerLabController::class, 'jadwal'])->name('jadwal');
    
    // Menu 6: Laporan
    Route::get('/laporan', [ComputerLabController::class, 'laporan'])->name('laporan');
});