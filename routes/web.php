<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PondokController;
use App\Http\Controllers\SuperAdmin\UujPayoutController;
use App\Http\Controllers\SuperAdmin\AdminPondokController;
use App\Http\Controllers\AdminPondok\DashboardController as AdminPondokDashboard;
use App\Http\Controllers\AdminPondok\SantriController;
use App\Http\Controllers\AdminPondok\OrangTuaController;
use App\Http\Controllers\AdminPondok\JenisPembayaranController;
use App\Http\Controllers\AdminPondok\ItemPembayaranController;
use App\Http\Controllers\AdminPondok\KeringananController;
use App\Http\Controllers\AdminPondok\GenerateTagihanController;
use App\Http\Controllers\AdminPondok\ManualPaymentController;
use App\Http\Controllers\OrangTua\MidtransPaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\AdminPondok\LaporanSetoranController;
use App\Http\Controllers\AdminPondok\SelectSearchController;
use App\Http\Controllers\AdminPondok\TunggakanController;
use App\Http\Controllers\AdminPondok\SetoranController;
use App\Http\Controllers\AdminPondok\BendaharaController;
use App\Http\Controllers\Bendahara\SetoranController as BendaharaSetoranController;
use App\Http\Controllers\AdminPondok\LaporanBulananController;
use App\Http\Controllers\Bendahara\KasController;
use App\Http\Controllers\AdminPondok\PembatalanController;
use App\Http\Controllers\AdminPondok\BukuBesarController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\MidtransReportController;
use App\Http\Controllers\AdminPondok\PayoutController;
use App\Http\Controllers\SuperAdmin\PayoutController as SuperAdminPayoutController;
use App\Http\Controllers\AdminPondok\SettingController;
use App\Http\Controllers\AdminPondok\KelasController;
use App\Http\Controllers\AdminPondok\UangJajanController;
use App\Http\Controllers\AdminPondok\AdminUangJajanController;
use App\Http\Controllers\UangJajan\DashboardController as UjDashboardController;
use App\Http\Controllers\UangJajan\WarungController;
use App\Http\Controllers\UangJajan\DompetController;
use App\Http\Controllers\UangJajan\TransaksiManualController;
use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\OrangTua\DompetController as OrangTuaDompetController;
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\TunggakanController as BendaharaTunggakanController;
use App\Http\Controllers\AdminPondok\PengurusPondokController;

// --- TAMBAHAN BARU: MODUL SEKOLAH FORMAL ---
use App\Http\Controllers\AdminPondok\ManajemenSekolahController;
use App\Http\Controllers\Sekolah\SuperAdmin\DashboardController as SuperAdminSekolahDashboard;
use App\Http\Controllers\Sekolah\SuperAdmin\SekolahController as SuperAdminSekolahController;
use App\Http\Controllers\Sekolah\SuperAdmin\AdminSekolahController as SuperAdminAdminSekolahController;
use App\Http\Controllers\Sekolah\SuperAdmin\GuruController as SuperAdminGuruController;
use App\Http\Controllers\Sekolah\SuperAdmin\TahunAjaranController as SuperAdminTahunAjaranController;
use App\Http\Controllers\Sekolah\Admin\DashboardController as AdminSekolahDashboard;
use App\Http\Controllers\Sekolah\Admin\MataPelajaranController;
use App\Http\Controllers\Sekolah\Admin\JadwalPelajaranController;
use App\Http\Controllers\Sekolah\Admin\KegiatanAkademikController;
use App\Http\Controllers\Sekolah\Admin\KonfigurasiController;
use App\Http\Controllers\Sekolah\Admin\AbsensiMonitoringController;
use App\Http\Controllers\Sekolah\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Sekolah\Guru\AbsensiKehadiranController;
use App\Http\Controllers\Sekolah\Guru\JadwalMengajarController;
use App\Http\Controllers\Sekolah\Guru\AbsensiSiswaController;
use App\Http\Controllers\Sekolah\Guru\NilaiController;
use App\Http\Controllers\Sekolah\Guru\IzinController;
use App\Http\Controllers\Sekolah\Admin\PersetujuanIzinController;
use App\Http\Controllers\Sekolah\Admin\LaporanNilaiController;
// --- AKHIR TAMBAHAN ---


Route::get('/', function () {
    return view('welcome');
});

// === PERBAIKAN RUTE /DASHBOARD ===
Route::get('/dashboard', function () {
    $user = Auth::user();

    // Logika pengalihan berdasarkan peran (Role-Based Redirect)
    
    // Peran Super Admin
    if ($user->hasRole('super-admin')) {
        return redirect()->route('superadmin.dashboard');
    }

    // Peran Admin Pondok (Pengelola Utama)
    if ($user->hasRole('admin-pondok')) {
        return redirect()->route('adminpondok.dashboard');
    }

    if ($user->hasRole('super-admin-sekolah')) {
        return redirect()->route('sekolah.superadmin.dashboard');
    }
    if ($user->hasRole('admin-sekolah')) {
        return redirect()->route('sekolah.admin.dashboard');
    }
    if ($user->hasRole('guru')) {
        return redirect()->route('sekolah.guru.dashboard');
    }

    if ($user->hasRole('pengurus_pondok')) {
        return redirect()->route('pengurus.dashboard');
    }

    // Peran Admin Uang Jajan
    if ($user->hasRole('admin_uang_jajan')) {
        return redirect()->route('uuj-admin.dashboard');
    }

    // Peran POS Warung/Kasir
    if ($user->hasRole('pos_warung')) {
        // Arahkan langsung ke halaman kasir/scan utama
        return redirect()->route('pos.index');
    }

    // Peran Bendahara
    if ($user->hasRole('bendahara')) {
        return redirect()->route('bendahara.dashboard');
    }
    
    // Peran Orang Tua
    if ($user->hasRole('orang-tua')) {
        return redirect()->route('orangtua.dashboard');
    }

    // Peran Admin Pendidikan (Tambahan Baru)
    if ($user->hasRole('admin-pendidikan')) {
        return redirect()->route('pendidikan.admin.dashboard');
    }

    // Peran Ustadz (Tambahan Baru)
    if ($user->hasRole('ustadz')) {
        return redirect()->route('ustadz.dashboard');
    }

    // Fallback: Jika tidak ada role spesifik (Misal: role 'user' default atau ada role baru)
    // Biarkan tetap menampilkan view 'dashboard' default jika diperlukan.
    return view('dashboard');
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === WEBHOOK HANDLER ===
// Rute ini harus publik dan tidak boleh dilindungi CSRF
Route::post('/midtrans/webhook', 
            [\App\Http\Controllers\MidtransWebhookController::class, 'handle'])
            ->name('midtrans.webhook');

Route::get('/langganan-berakhir', function (Illuminate\Http\Request $request) {
    // Ambil pesan error dari session
    $error = $request->session()->get('error', 'Langganan Anda telah berakhir.');
    return view('auth.langganan-berakhir', ['error' => $error]);
})->name('langganan.berakhir');

require __DIR__.'/auth.php';



// === AREA SUPER ADMIN ===
Route::middleware(['auth', 'role:super-admin'])->prefix('superadmin')->name('superadmin.')->group(function () {

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

Route::get('uuj-payouts', [\App\Http\Controllers\SuperAdmin\UujPayoutController::class, 'index'])->name('uuj-payout.index');
    Route::put('uuj-payouts/{id}', [\App\Http\Controllers\SuperAdmin\UujPayoutController::class, 'update'])->name('uuj-payout.update');
});

// === API INTERNAL (UNTUK PENCARIAN) ===
Route::middleware(['auth', 'role:admin-pondok|admin_uang_jajan', 'cek.langganan'])
     ->prefix('api-internal') // Kita beri prefix baru
     ->name('api.')
     ->group(function () {

    // Ini adalah rute baru kita, bisa diakses oleh 2 role
    Route::get('/search-santri', [SelectSearchController::class, 'searchSantri'])
         ->name('search.santri');
});

// === AREA ADMIN PONDOK ===
Route::middleware(['auth', 'role:admin-pondok', 'cek.langganan'])
     ->prefix('adminpondok')
     ->name('adminpondok.')
     ->group(function () {

    Route::get('/dashboard', [AdminPondokDashboard::class, 'index'])->name('dashboard');

    Route::resource('santris', SantriController::class);
    Route::resource('orang-tuas', OrangTuaController::class);
    Route::resource('jenis-pembayarans', JenisPembayaranController::class);
    Route::resource('keringanans', KeringananController::class);

// Rute untuk Item Pembayaran (Nested)
Route::resource('jenis-pembayarans', JenisPembayaranController::class);
Route::resource('jenis-pembayarans.items', ItemPembayaranController::class)
     ->shallow(); // 'shallow' membuat URL lebih pendek untuk edit/update

Route::get('setoran', [SetoranController::class, 'index'])->name('setoran.index');       // Halaman Laporan (BARU)
Route::get('setoran/create', [SetoranController::class, 'create'])->name('setoran.create'); // Halaman Form (BARU)
Route::post('setoran', [SetoranController::class, 'store'])->name('setoran.store');     // Logika Simpan
Route::get('setoran/history', [SetoranController::class, 'history'])->name('setoran.history'); // Riwayat
Route::get('setoran/{setoran}', [SetoranController::class, 'show'])->name('setoran.show');
    Route::get('setoran/{setoran}/pdf', [SetoranController::class, 'downloadPDF'])->name('setoran.pdf');
// Rute untuk Generate Tagihan
Route::get('generate-tagihan', [GenerateTagihanController::class, 'create'])->name('tagihan.create');
Route::post('generate-tagihan', [GenerateTagihanController::class, 'store'])->name('tagihan.store');

// Rute untuk Manajemen Tagihan (List/Read/Delete)
Route::resource('tagihan', \App\Http\Controllers\AdminPondok\TagihanController::class)->except(['create', 'store', 'edit', 'update']);
Route::post('tagihan/{tagihan}/pay-manual', [ManualPaymentController::class, 'store'])
     ->name('tagihan.pay-manual');
Route::get('laporan-setoran', [LaporanSetoranController::class, 'index'])->name('laporan.index');
Route::post('laporan-setoran', [LaporanSetoranController::class, 'store'])->name('laporan.store');
Route::get('laporan-setoran/history', [LaporanSetoranController::class, 'history'])->name('laporan.history');
Route::get('laporan-tunggakan', [TunggakanController::class, 'index'])->name('laporan.tunggakan');
Route::post('santri/{santri}/generate-future-tagihan', 
            [\App\Http\Controllers\AdminPondok\GenerateTagihanController::class, 'storeFuture'])
            ->name('tagihan.store-future');
Route::resource('bendahara', BendaharaController::class);
Route::get('laporan-bulanan', [LaporanBulananController::class, 'index'])->name('laporan.bulanan');
Route::get('laporan-bulanan/pdf', [LaporanBulananController::class, 'downloadPDF'])->name('laporan.bulanan.pdf');
Route::post('transaksi/{transaksi}/cancel', [PembatalanController::class, 'cancel'])->name('transaksi.cancel');
Route::get('buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');
Route::get('penarikan-midtrans', [PayoutController::class, 'index'])->name('payout.index');
Route::post('penarikan-midtrans', [PayoutController::class, 'store'])->name('payout.store');
Route::get('penarikan-midtrans/{payout}', [PayoutController::class, 'show'])->name('payout.show'); // <-- TAMBAHKAN INI
Route::delete('penarikan-midtrans/{payout}', [PayoutController::class, 'destroy'])->name('payout.destroy');
// Rute Pengaturan
Route::get('pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
Route::post('pengaturan', [SettingController::class, 'store'])->name('pengaturan.store');

// Rute CRUD untuk Kelas
Route::resource('kelas', KelasController::class);


// ========================================================
    // FITUR PREMIUM (Protected by isPremium Middleware)
    // ========================================================
    Route::middleware(['isPremium'])->group(function () {
        
        // --- 1. GRUP UANG JAJAN (UUJ) ---
        // Prefix URL: /admin-pondok/uuj/..
        // Nama Route: adminpondok.uuj...
        Route::prefix('uuj')->name('uuj.')->group(function () {
            // Akun Admin Uang Jajan (Pengelola)
            Route::resource('admin', App\Http\Controllers\AdminPondok\AdminUangJajanController::class);
            // (Ini akan menghasilkan route: adminpondok.uuj.admin.index, create, dll)
        });

        // Payout (Dompet Midtrans)
        Route::resource('payout', App\Http\Controllers\AdminPondok\PayoutController::class);


        // --- 2. GRUP MANAJEMEN KESANTRIAN (PENGURUS) ---
        // Prefix URL: /admin-pondok/kesantrian/..
        // Route resource: 'pengurus'
        // Nama Route Hasil: adminpondok.pengurus.index
        // (Kita tidak pakai prefix name 'kesantrian.' agar sesuai dengan view yang sudah dibuat)
        Route::resource('pengurus', App\Http\Controllers\AdminPondok\PengurusPondokController::class)
            ->parameters(['pengurus' => 'pengurus']); 
            // ^-- INI SOLUSINYA! Memaksa parameter jadi {pengurus} agar cocok dengan controller.
        Route::resource('manajemen-sekolah', ManajemenSekolahController::class)->parameters([
                'manajemen-sekolah' => 'user' // Ini akan mengubah {manajemen_sekolah} menjadi {user}
            ]);
    });

}); // <-- Tutup Group Admin Pondok

// === AREA ORANG TUA / WALI SANTRI ===
Route::middleware(['auth', 'role:orang-tua'])
     ->prefix('orangtua')
     ->name('orangtua.')
     ->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\OrangTua\DashboardController::class, 'index'])
         ->name('dashboard');

    // (Nanti rute tagihan kita letakkan di sini)
    Route::get('/tagihan', [\App\Http\Controllers\OrangTua\TagihanController::class, 'index'])
         ->name('tagihan.index');
    Route::get('/tagihan/{tagihan}', [\App\Http\Controllers\OrangTua\TagihanController::class, 'show'])
         ->name('tagihan.show');
    // 1. Halaman untuk memilih metode bayar
Route::get('tagihan/{tagihan}/bayar', [MidtransPaymentController::class, 'pilihMetode'])
     ->name('tagihan.pilih-metode');

// 2. Proses permintaan pembayaran ke Midtrans
Route::post('tagihan/{tagihan}/bayar', [MidtransPaymentController::class, 'prosesPembayaran'])
     ->name('tagihan.proses-pembayaran');

// 3. Halaman instruksi (menampilkan VA/QR)
Route::get('pembayaran/{transaksi}/instruksi', [MidtransPaymentController::class, 'instruksiPembayaran'])
     ->name('pembayaran.instruksi');
Route::get('pembayaran/{transaksi}/status', [MidtransPaymentController::class, 'checkStatus'])
         ->name('pembayaran.status');

         
Route::get('dompet', [OrangTuaDompetController::class, 'index'])->name('dompet.index');
    Route::get('dompet/topup', [OrangTuaDompetController::class, 'createTopup'])->name('dompet.topup.create');
    Route::post('dompet/topup', [OrangTuaDompetController::class, 'storeTopup'])->name('dompet.topup.store');
Route::put('dompet/{dompet}', [OrangTuaDompetController::class, 'update'])->name('dompet.update');

Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/{id}', [App\Http\Controllers\OrangTua\MonitoringController::class, 'index'])->name('index');
        Route::get('/{id}/kesehatan', [App\Http\Controllers\OrangTua\MonitoringController::class, 'kesehatan'])->name('kesehatan');
        Route::get('/{id}/izin', [App\Http\Controllers\OrangTua\MonitoringController::class, 'izin'])->name('izin');
        Route::get('/{id}/gerbang', [App\Http\Controllers\OrangTua\MonitoringController::class, 'gerbang'])->name('gerbang');
        Route::get('/{id}/absensi', [App\Http\Controllers\OrangTua\MonitoringController::class, 'absensi'])->name('absensi');
    });
});

// === AREA BENDAHARA ===
Route::middleware(['auth', 'role:bendahara', 'cek.langganan'])
     ->prefix('bendahara')
     ->name('bendahara.')
     ->group(function () {
    
    // INI RUTE YANG HILANG:
    Route::get('/dashboard', [\App\Http\Controllers\Bendahara\DashboardController::class, 'index'])
         ->name('dashboard');
    
    // Rute untuk mengelola setoran (yang mungkin sudah Anda tambahkan)
    Route::get('/setoran-masuk', [BendaharaSetoranController::class, 'index'])
         ->name('setoran.index');
    Route::get('/setoran/{setoran}', [BendaharaSetoranController::class, 'show'])
         ->name('setoran.show');
    Route::post('/setoran/{setoran}/konfirmasi', [BendaharaSetoranController::class, 'konfirmasi'])
         ->name('setoran.konfirmasi');
     Route::resource('kas', KasController::class)->except(['show']);
     Route::get('kas/pdf', [KasController::class, 'downloadPDF'])->name('kas.pdf'); // <-- TAMBAHKAN INI
     Route::get('/setoran/{setoran}/pdf', [BendaharaSetoranController::class, 'downloadPDF'])
         ->name('setoran.pdf');
     Route::get('/tunggakan', [\App\Http\Controllers\Bendahara\TunggakanController::class, 'index'])
         ->name('tunggakan.index');
     Route::get('/tunggakan/{santri}', [\App\Http\Controllers\Bendahara\TunggakanController::class, 'show'])
         ->name('tunggakan.show');
});

// === AREA ADMIN UANG JAJAN ===
Route::middleware(['auth', 'role:admin_uang_jajan', 'cek.langganan', 'isPremium'])
     ->prefix('uuj-admin') // 'uuj' = Uang Uang Jajan
     ->name('uuj-admin.')
     ->group(function () {
    
    Route::get('/dashboard', [UjDashboardController::class, 'index'])
         ->name('dashboard');
         
    // Rute untuk CRUD Warung
    Route::resource('warung', WarungController::class);

    // Rute untuk Manajemen Dompet
Route::get('dompet', [DompetController::class, 'index'])->name('dompet.index');
Route::get('dompet/{santri}/activate', [DompetController::class, 'activate'])->name('dompet.activate'); // Halaman aktivasi
Route::post('dompet/{santri}', [DompetController::class, 'store'])->name('dompet.store'); // Proses aktivasi
Route::get('dompet/{dompet}/edit', [DompetController::class, 'edit'])->name('dompet.edit'); // Halaman edit (blokir, ganti PIN, ganti barcode)
Route::put('dompet/{dompet}', [DompetController::class, 'update'])->name('dompet.update');
// Rute untuk Top-up Manual
Route::get('topup', [TransaksiManualController::class, 'createTopup'])->name('topup.create');
Route::post('topup', [TransaksiManualController::class, 'storeTopup'])->name('topup.store');

// Rute untuk Tarik Tunai
Route::get('tarik-tunai', [TransaksiManualController::class, 'createTarik'])->name('tarik.create');
Route::post('tarik-tunai', [TransaksiManualController::class, 'storeTarik'])->name('tarik.store');
// RUTE BARU: Manajemen Penarikan Warung
    Route::get('payout-warung', [\App\Http\Controllers\UangJajan\WarungPayoutController::class, 'index'])
         ->name('payout.index');
    
    Route::post('payout-warung/{id}/approve', [\App\Http\Controllers\UangJajan\WarungPayoutController::class, 'approve'])
         ->name('payout.approve');
         
    Route::post('payout-warung/{id}/reject', [\App\Http\Controllers\UangJajan\WarungPayoutController::class, 'reject'])
         ->name('payout.reject');
Route::post('tarik-tunai/find-santri', [TransaksiManualController::class, 'findSantri'])->name('tarik.find-santri');
Route::get('pencairan-dana', [\App\Http\Controllers\UangJajan\UujPayoutController::class, 'index'])->name('pencairan.index');
    Route::post('pencairan-dana', [\App\Http\Controllers\UangJajan\UujPayoutController::class, 'store'])->name('pencairan.store');
});

// === AREA KASIR WARUNG / POS ===
Route::middleware(['auth', 'role:pos_warung', 'cek.langganan', 'isPremium'])
     ->prefix('pos')
     ->name('pos.')
     ->group(function () {

    // Dashboard & Kasir
    Route::get('/dashboard', [PosController::class, 'dashboard'])->name('dashboard');
    Route::get('/kasir', [PosController::class, 'index'])->name('index'); // Halaman Scan (POS Utama)

    // Fitur Baru
    Route::get('/riwayat', [PosController::class, 'history'])->name('history');
    Route::get('/penarikan', [PosController::class, 'payout'])->name('payout');
    Route::post('/penarikan', [PosController::class, 'storePayout'])->name('payout.store');

    // API Internal
    Route::post('/find-santri', [PosController::class, 'findSantri'])->name('find-santri');
    Route::post('/process-transaction', [PosController::class, 'processTransaction'])->name('process-transaction');
    Route::get('/penarikan/{id}', [PosController::class, 'showPayout'])->name('payout.show');
});

// ====================================================
// 7. GROUP PENGURUS PONDOK (MOBILE APP)
// ====================================================
Route::middleware(['auth', 'role:pengurus_pondok'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Pengurus\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('santri', App\Http\Controllers\Pengurus\SantriController::class);
    Route::post('santri/{santri}/regenerate-qr', [App\Http\Controllers\Pengurus\SantriController::class, 'regenerateQR'])
        ->name('santri.regenerate-qr');
    Route::get('uks', [App\Http\Controllers\Pengurus\UksController::class, 'index'])->name('uks.index');
    Route::get('uks/history', [App\Http\Controllers\Pengurus\UksController::class, 'history'])->name('uks.history');
    Route::get('uks/scan', [App\Http\Controllers\Pengurus\UksController::class, 'create'])->name('uks.scan');
    Route::post('uks/scan', [App\Http\Controllers\Pengurus\UksController::class, 'processScan'])->name('uks.process-scan'); // Cek Kartu
    Route::post('uks/store', [App\Http\Controllers\Pengurus\UksController::class, 'store'])->name('uks.store'); // Simpan Data
    Route::get('uks/{uks}', [App\Http\Controllers\Pengurus\UksController::class, 'show'])->name('uks.show');
    Route::get('uks/{uks}/edit', [App\Http\Controllers\Pengurus\UksController::class, 'edit'])->name('uks.edit');
    Route::put('uks/{uks}', [App\Http\Controllers\Pengurus\UksController::class, 'update'])->name('uks.update');
    Route::get('perizinan/history', [App\Http\Controllers\Pengurus\PerizinanController::class, 'history'])->name('perizinan.history'); // <--- TAMBAHKAN INI
    Route::resource('perizinan', App\Http\Controllers\Pengurus\PerizinanController::class);
    Route::get('perizinan-scan', [App\Http\Controllers\Pengurus\PerizinanController::class, 'create'])->name('perizinan.scan');
    Route::post('perizinan-process', [App\Http\Controllers\Pengurus\PerizinanController::class, 'processScan'])->name('perizinan.process');

    // Route Download Template
    Route::get('santri/template/download', [App\Http\Controllers\Pengurus\SantriController::class, 'downloadTemplate'])
        ->name('santri.template');

    // Route Proses Import
    Route::post('santri/import', [App\Http\Controllers\Pengurus\SantriController::class, 'import'])
        ->name('santri.import');

    Route::resource('santri', App\Http\Controllers\Pengurus\SantriController::class);
    
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [App\Http\Controllers\Pengurus\AbsensiController::class, 'index'])->name('index');
        
        // Sub-Menu Routes
        Route::get('/gerbang', [App\Http\Controllers\Pengurus\AbsensiController::class, 'gerbang'])->name('gerbang');
        Route::get('/asrama', [App\Http\Controllers\Pengurus\AbsensiController::class, 'asrama'])->name('asrama');
        Route::get('/kegiatan', [App\Http\Controllers\Pengurus\AbsensiController::class, 'kegiatan'])->name('kegiatan');
        Route::get('/jamaah', [App\Http\Controllers\Pengurus\AbsensiController::class, 'jamaah'])->name('jamaah');
        Route::get('/kontrol', [App\Http\Controllers\Pengurus\AbsensiController::class, 'kontrol'])->name('kontrol');
        Route::get('/asrama', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'index'])->name('asrama');
        Route::get('/asrama/rekap', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'rekap'])->name('asrama.rekap');
        Route::get('/asrama/settings', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'settings'])->name('asrama.settings');
        Route::post('/asrama/settings', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'storeSettings'])->name('asrama.settings.store');
        Route::post('/asrama/libur', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'storeLibur'])->name('asrama.libur.store');
        Route::delete('/asrama/libur/{id}', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'deleteLibur'])->name('asrama.libur.delete');
        
        Route::get('/asrama/scan', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'scan'])->name('asrama.scan');
        Route::post('/asrama/process', [App\Http\Controllers\Pengurus\Absensi\AsramaController::class, 'processScan'])->name('asrama.process');
        // Kegiatan
    Route::get('/kegiatan', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'index'])->name('kegiatan');
    Route::get('/kegiatan/settings', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'settings'])->name('kegiatan.settings');
    Route::post('/kegiatan/settings', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'storeSettings'])->name('kegiatan.settings.store');
    Route::delete('/kegiatan/{id}', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'delete'])->name('kegiatan.delete');

    Route::get('/kegiatan/scan', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'scan'])->name('kegiatan.scan');
    Route::post('/kegiatan/process', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'processScan'])->name('kegiatan.process');

    Route::get('/kegiatan/rekap', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'rekapList'])->name('kegiatan.rekap');
    Route::get('/kegiatan/rekap/{id}', [App\Http\Controllers\Pengurus\Absensi\KegiatanController::class, 'rekapShow'])->name('kegiatan.rekap.show');
    // Jamaah
        Route::get('/jamaah', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'index'])->name('jamaah');
        Route::get('/kontrol', [App\Http\Controllers\Pengurus\Absensi\KontrolController::class, 'index'])->name('kontrol');
        Route::get('/kontrol/pdf', [App\Http\Controllers\Pengurus\Absensi\KontrolController::class, 'downloadPDF'])->name('kontrol.pdf');
        
        // Manajemen Haid
        Route::get('/jamaah/haid', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'haidIndex'])->name('jamaah.haid');
        Route::post('/jamaah/haid', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'haidStore'])->name('jamaah.haid.store');
        Route::put('/jamaah/haid/{id}/finish', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'haidFinish'])->name('jamaah.haid.finish');

        // Scanner
        Route::get('/jamaah/scan', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'scan'])->name('jamaah.scan');
        Route::post('/jamaah/process', [App\Http\Controllers\Pengurus\Absensi\JamaahController::class, 'processScan'])->name('jamaah.process');

        // Gerbang
        Route::get('/gerbang', [App\Http\Controllers\Pengurus\Absensi\GateController::class, 'index'])->name('gerbang');
        Route::get('/gerbang/settings', [App\Http\Controllers\Pengurus\Absensi\GateController::class, 'settings'])->name('gerbang.settings');
        Route::post('/gerbang/settings', [App\Http\Controllers\Pengurus\Absensi\GateController::class, 'storeSettings'])->name('gerbang.settings.store');
        // Route API (POST /gate/api/scan) dibuat di luar group middleware auth jika IoT tidak login,
        // Tapi di sini kita buat di controller yg sama
        
    });

    Route::prefix('asrama')->name('asrama.')->group(function () {
        Route::get('/', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'index'])->name('index');
        Route::get('/list/{gender}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'list'])->name('list');
        Route::post('/store', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'show'])->name('show');
        Route::get('/settings/{id}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'settings'])->name('settings'); // Halaman Edit
        Route::put('/update/{id}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'destroy'])->name('destroy');
        Route::put('/update/{id}', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'update'])->name('update');
        
        // Member
        Route::post('/{id}/add-member', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'addMember'])->name('member.add');
        Route::delete('/member/{santriId}/remove', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'removeMember'])->name('member.remove');

        Route::get('/pdf-data', [App\Http\Controllers\Pengurus\ManajemenAsramaController::class, 'downloadPDF'])->name('pdf.data');

        // ABSENSI KETUA ASRAMA
        Route::prefix('ketua')->name('ketua.')->group(function() {
            Route::get('/', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'index'])->name('index'); // Scan
            Route::post('/process', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'process'])->name('process');
            
            Route::get('/settings', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'settings'])->name('settings');
            Route::post('/settings', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'storeSettings'])->name('settings.store');
            
            Route::get('/rekap', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'rekap'])->name('rekap');
            Route::get('/rekap/{id}', [App\Http\Controllers\Pengurus\Absensi\AbsensiKetuaController::class, 'rekapDetail'])->name('rekap.detail');
        });
    });

    // === INVENTARIS ===
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        
        // Dashboard Inventaris (Menu Utama)
        Route::get('/', [App\Http\Controllers\Pengurus\Inventaris\InventarisController::class, 'index'])->name('index');

        // 1. Pengaturan Lokasi
        Route::resource('lokasi', App\Http\Controllers\Pengurus\Inventaris\LokasiController::class);

        // 2. Data Barang
        Route::resource('barang', App\Http\Controllers\Pengurus\Inventaris\BarangController::class);
        Route::get('barang/lokasi/{id}', [App\Http\Controllers\Pengurus\Inventaris\BarangController::class, 'byLokasi'])->name('barang.by_lokasi');
        
        // 3. Kerusakan
        Route::resource('kerusakan', App\Http\Controllers\Pengurus\Inventaris\KerusakanController::class);
        Route::post('kerusakan/{id}/resolve', [App\Http\Controllers\Pengurus\Inventaris\KerusakanController::class, 'resolve'])->name('kerusakan.resolve'); // Selesai/Ganti

        // 4. Peminjaman
        Route::resource('peminjaman', App\Http\Controllers\Pengurus\Inventaris\PeminjamanController::class);
        Route::post('peminjaman/{id}/return', [App\Http\Controllers\Pengurus\Inventaris\PeminjamanController::class, 'kembali'])->name('peminjaman.return');

        // 5. Rekap / Audit
        Route::get('rekap', [App\Http\Controllers\Pengurus\Inventaris\AuditController::class, 'index'])->name('rekap.index');
        Route::get('rekap/scan', [App\Http\Controllers\Pengurus\Inventaris\AuditController::class, 'scan'])->name('rekap.scan'); // Halaman Scan
        Route::post('rekap/process', [App\Http\Controllers\Pengurus\Inventaris\AuditController::class, 'process'])->name('rekap.process'); // Proses Input Jumlah
        Route::post('rekap/{id}/reconcile', [App\Http\Controllers\Pengurus\Inventaris\AuditController::class, 'reconcile'])->name('rekap.reconcile'); // Rekonsiliasi Selisih
        Route::get('rekap/pdf', [App\Http\Controllers\Pengurus\Inventaris\AuditController::class, 'downloadPDF'])->name('rekap.pdf');
    });
});

Route::post('/gerbang/api/scan', [App\Http\Controllers\Pengurus\Absensi\GateController::class, 'apiScan'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]) 
    // atau ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]) tergantung versi Laravel
    ->name('gerbang.api.scan');


// Salin dan tempel ini di file routes/web.php Anda (di luar grup lain)

// ==========================================================
// RUTE MODUL SEKOLAH FORMAL (FITUR PREMIUM)
// ==========================================================

// 1. Rute untuk SUPER ADMIN SEKOLAH (Mengelola semua sekolah di 1 pondok)
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:super-admin-sekolah'])
    ->prefix('sekolah-superadmin')
    ->name('sekolah.superadmin.')
    ->group(function () {
        
        Route::get('/dashboard', [SuperAdminSekolahDashboard::class, 'index'])->name('dashboard');

        // CRUD Unit Sekolah (MTS, MA, dll)
        Route::resource('sekolah', SuperAdminSekolahController::class);
        // CRUD Akun Admin Sekolah
        Route::resource('admin-sekolah', SuperAdminAdminSekolahController::class)->parameters([
            'admin-sekolah' => 'user' // Ini akan mengubah {admin_sekolah} menjadi {user}
        ]);

        Route::resource('rapor-template', App\Http\Controllers\Pendidikan\RaporTemplateController::class);

        Route::get('persetujuan-izin', [PersetujuanIzinController::class, 'index'])->name('persetujuan-izin.index');
        Route::post('persetujuan-izin/{sekolahIzinGuru}/approve', [PersetujuanIzinController::class, 'approve'])->name('persetujuan-izin.approve');
        Route::post('persetujuan-izin/{sekolahIzinGuru}/reject', [PersetujuanIzinController::class, 'reject'])->name('persetujuan-izin.reject');
        // CRUD Akun dan Profil Guru
        Route::resource('guru', SuperAdminGuruController::class)->parameters([
            'guru' => 'user' // Mengubah {guru} -> {user}
        ]);
        
        // CRUD Tahun Ajaran (Ini aman, karena akan menggunakan {tahun_ajaran})
        // CRUD Tahun Ajaran
        Route::resource('tahun-ajaran', SuperAdminTahunAjaranController::class)->except(['show']);
        Route::resource('admin-pendidikan', \App\Http\Controllers\Sekolah\SuperAdmin\AdminPendidikanController::class);
        
        // Rute khusus untuk tombol "Jadikan Aktif"
        Route::post('tahun-ajaran/{tahunAjaran}/activate', [SuperAdminTahunAjaranController::class, 'activate'])
             ->name('tahun-ajaran.activate');
    });

// Salin dan Ganti seluruh blok kode rute 'admin-sekolah' dan 'guru' Anda dengan ini:

// 2. Rute untuk ADMIN SEKOLAH (Mengelola 1 sekolah spesifik)
// (TIDAK ADA PERUBAHAN DI SINI, SUDAH BENAR)
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:admin-sekolah'])
    ->prefix('sekolah-admin')
    ->name('sekolah.admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminSekolahDashboard::class, 'index'])->name('dashboard');

        // CRUD Mata Pelajaran
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        // CRUD Jadwal Pelajaran
        Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);
        // CRUD Jadwal Ujian
        Route::resource('kegiatan-akademik', KegiatanAkademikController::class)->except(['show']);

        // --- TAMBAHAN BARU: JALUR KELOLA NILAI ---
        Route::prefix('kegiatan-akademik/{kegiatan}')->name('kegiatan-akademik.')->group(function () {
            // Tampilan 1: Daftar Kelas (setelah klik 'Kelola')
            Route::get('kelola', [LaporanNilaiController::class, 'showKelas'])->name('kelola.kelas');
            
            // Tampilan 2: Daftar Mapel (setelah klik Kelas)
            Route::get('kelas/{kelas}/mapel', [LaporanNilaiController::class, 'showMapel'])->name('kelola.mapel');
            
            // Tampilan 3: Tabel Nilai (setelah klik Mapel)
            Route::get('kelas/{kelas}/mapel/{mapel}/nilai', [LaporanNilaiController::class, 'showNilaiTable'])->name('kelola.nilai');
            
            // Rute Cetak PDF
            Route::get('pdf/kelas/{kelas}/mapel/{mapel}', [LaporanNilaiController::class, 'cetakLedgerNilai'])->name('cetak.nilai');

            Route::get('pdf/kelas/{kelas}/mapel/{mapel}/format', [LaporanNilaiController::class, 'cetakFormatNilai'])->name('cetak.format-nilai');
            
            // Cetak Daftar Hadir Ujian
            Route::get('pdf/kelas/{kelas}/mapel/{mapel}/hadir', [LaporanNilaiController::class, 'cetakDaftarHadir'])->name('cetak.daftar-hadir');
        });

        Route::resource('kelas', \App\Http\Controllers\Sekolah\Admin\KelasController::class);

        Route::get('guru-pengganti', [App\Http\Controllers\Sekolah\Admin\GuruPenggantiController::class, 'index'])->name('guru-pengganti.index');
        Route::post('guru-pengganti', [App\Http\Controllers\Sekolah\Admin\GuruPenggantiController::class, 'store'])->name('guru-pengganti.store');

        Route::get('kinerja/guru', [App\Http\Controllers\Sekolah\Admin\KinerjaGuruController::class, 'index'])->name('kinerja.guru');
        Route::get('kinerja/siswa', [App\Http\Controllers\Sekolah\Admin\KinerjaSiswaController::class, 'index'])->name('kinerja.siswa');

        Route::get('laporan', [App\Http\Controllers\Sekolah\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::post('laporan/cetak', [App\Http\Controllers\Sekolah\Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');
        
        // Fitur Naik Kelas
        Route::get('naik-kelas', [\App\Http\Controllers\Sekolah\Admin\KelasController::class, 'naikKelasView'])->name('kelas.naikKelas.view');
        Route::post('naik-kelas', [\App\Http\Controllers\Sekolah\Admin\KelasController::class, 'naikKelasProcess'])->name('kelas.naikKelas.process');
        
        // Rute baru untuk aksi di dalam halaman 'show' kelas
        Route::post('kelas/{kela}/add-santri', [\App\Http\Controllers\Sekolah\Admin\KelasController::class, 'addSantri'])->name('kelas.addSantri');
        Route::post('santri/{santri}/move-santri', [\App\Http\Controllers\Sekolah\Admin\KelasController::class, 'moveSantri'])->name('kelas.moveSantri');
    
        // Rute Konfigurasi
        Route::prefix('konfigurasi')->name('konfigurasi.')->group(function () {
            // Halaman utama (sekarang menampilkan 4 modul: Jam, Hari Libur, WiFi, Geofence)
            Route::get('/', [KonfigurasiController::class, 'index'])->name('index');
            
            // Rute simpan pengaturan jam & hari kerja
            Route::post('settings', [KonfigurasiController::class, 'storeSettings'])->name('settings.store');

            // Rute CRUD Hari Libur (kecuali index & show, karena nempel di 'index')
            Route::post('hari-libur', [KonfigurasiController::class, 'storeHariLibur'])->name('hari-libur.store');
            Route::delete('hari-libur/{sekolahHariLibur}', [KonfigurasiController::class, 'destroyHariLibur'])->name('hari-libur.destroy');

            // Rute untuk WiFi (tetap sama)
            Route::post('wifi', [KonfigurasiController::class, 'storeWifi'])->name('wifi.store');
            Route::delete('wifi/{sekolahWifi}', [KonfigurasiController::class, 'destroyWifi'])->name('wifi.destroy');
            
            // Rute untuk Geofence (tetap sama)
            Route::post('geofence', [KonfigurasiController::class, 'storeGeofence'])->name('geofence.store');
            Route::delete('geofence/{sekolahLokasiGeofence}', [KonfigurasiController::class, 'destroyGeofence'])->name('geofence.destroy');

            Route::get('kios-kode', [KonfigurasiController::class, 'showKiosKode'])->name('kios.show');
        Route::get('kios-kode/new', [KonfigurasiController::class, 'getNewKodeAbsen'])->name('kios.new_kode');
        });

        // Rute Monitoring Absensi
        Route::prefix('monitoring-absensi')->name('monitoring.')->group(function () {
            Route::get('guru', [AbsensiMonitoringController::class, 'rekapGuru'])->name('guru');
            Route::get('siswa', [AbsensiMonitoringController::class, 'rekapSiswa'])->name('siswa');
        });

        
    });

// 3. Rute untuk GURU (Panel Operasional Harian)
// (PERBAIKAN NAMA PARAMETER DI GRUP INI)
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:guru'])
    ->prefix('sekolah-guru')
    ->name('sekolah.guru.')
    ->group(function () {
        
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

        // Absensi Kehadiran (Masuk Sekolah)
        Route::get('absensi-kehadiran', [AbsensiKehadiranController::class, 'index'])->name('absensi.kehadiran.index');
        Route::post('absensi-kehadiran', [AbsensiKehadiranController::class, 'store'])->name('absensi.kehadiran.store');

        // Jadwal & Mengajar
        // Jadwal & Mengajar
        Route::get('jadwal-mengajar', [JadwalMengajarController::class, 'index'])->name('jadwal.index');
        Route::get('jadwal-mengajar/{jadwal_pelajaran}', [JadwalMengajarController::class, 'show'])->name('jadwal.show');
        Route::post('jadwal-mengajar/absen-masuk', [JadwalMengajarController::class, 'storeAbsenMengajar'])->name('jadwal.absen');
        Route::post('jadwal-mengajar/simpan-materi/{absensi_pelajaran}', [JadwalMengajarController::class, 'storeMateri'])->name('jadwal.absen.materi');

        // Absensi Siswa (di dalam kelas)
        // PERBAIKAN: {absensi_pelajaran_id} -> {absensi_pelajaran}
        Route::get('absensi-siswa/{absensi_pelajaran}', [AbsensiSiswaController::class, 'index'])->name('absensi.siswa.index');
        Route::post('absensi-siswa', [AbsensiSiswaController::class, 'store'])->name('absensi.siswa.store');

        // Input Nilai
        Route::get('input-nilai', [App\Http\Controllers\Sekolah\Guru\NilaiController::class, 'index'])->name('nilai.index');
        Route::get('input-nilai/{kegiatan}/kelas', [App\Http\Controllers\Sekolah\Guru\NilaiController::class, 'showKelas'])->name('nilai.kelas');
        Route::get('input-nilai/{kegiatan}/kelas/{kelasId}/mapel/{mapelId}', [App\Http\Controllers\Sekolah\Guru\NilaiController::class, 'formNilai'])->name('nilai.form');
        Route::post('input-nilai/{kegiatan}/store', [App\Http\Controllers\Sekolah\Guru\NilaiController::class, 'store'])->name('nilai.store');
        Route::get('izin', [IzinController::class, 'index'])->name('izin.index');
        Route::get('izin/create', [IzinController::class, 'create'])->name('izin.create');
        Route::post('izin', [IzinController::class, 'store'])->name('izin.store');
    });

    // ==========================================================
// MODUL PENDIDIKAN PESANTREN (MADIN/DINIYAH)
// ==========================================================

// 1. AREA ADMIN PENDIDIKAN (Pengelola Data)
// Diakses via Laptop/PC
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:admin-pendidikan'])
    ->prefix('pendidikan-admin')
    ->name('pendidikan.admin.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Pendidikan\DashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('mustawa', App\Http\Controllers\Pendidikan\MustawaController::class);
        Route::resource('mapel', App\Http\Controllers\Pendidikan\MapelDiniyahController::class);
        Route::resource('ustadz', App\Http\Controllers\Pendidikan\UstadzController::class);

        Route::resource('rapor-template', App\Http\Controllers\Pendidikan\RaporTemplateController::class);
        
        // Route untuk Generate Rapor (Yang tadi kita buat di RaporController)
        Route::get('rapor', [App\Http\Controllers\Pendidikan\RaporController::class, 'index'])->name('rapor.index');
        Route::post('rapor/generate', [App\Http\Controllers\Pendidikan\RaporController::class, 'generate'])->name('rapor.generate');

        // Akademik & Jadwal
        Route::resource('jadwal', App\Http\Controllers\Pendidikan\JadwalDiniyahController::class);

        // Manajemen Anggota Kelas
Route::get('anggota-kelas', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'index'])->name('anggota-kelas.index');
Route::get('anggota-kelas/{mustawa}', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'show'])->name('anggota-kelas.show');
Route::post('anggota-kelas/{mustawa}/add', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'store'])->name('anggota-kelas.store');
Route::delete('anggota-kelas/{mustawa}/remove/{santri}', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'destroy'])->name('anggota-kelas.destroy');

// Kenaikan Kelas
Route::get('kenaikan-kelas', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'promotionIndex'])->name('kenaikan-kelas.index');
Route::get('kenaikan-kelas/check', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'promotionCheck'])->name('kenaikan-kelas.check');
Route::post('kenaikan-kelas/process', [App\Http\Controllers\Pendidikan\AnggotaKelasController::class, 'promotionStore'])->name('kenaikan-kelas.store');

// Jadwal Ujian
Route::resource('ujian', App\Http\Controllers\Pendidikan\JadwalUjianController::class);
// Detail Aksi Jadwal Ujian
Route::get('ujian/{ujian}/kelola', [App\Http\Controllers\Pendidikan\JadwalUjianController::class, 'show'])->name('ujian.show');
Route::post('ujian/{ujian}/attendance', [App\Http\Controllers\Pendidikan\JadwalUjianController::class, 'storeAttendance'])->name('ujian.attendance');
Route::post('ujian/{ujian}/grades', [App\Http\Controllers\Pendidikan\JadwalUjianController::class, 'storeGrades'])->name('ujian.grades');
Route::get('ujian/{ujian}/pdf', [App\Http\Controllers\Pendidikan\JadwalUjianController::class, 'exportPdf'])->name('ujian.pdf');
Route::get('ujian/{ujian}/excel', [App\Http\Controllers\Pendidikan\JadwalUjianController::class, 'exportExcel'])->name('ujian.excel');
        
        // Monitoring & Laporan
        Route::get('absensi/rekap', [App\Http\Controllers\Pendidikan\AbsensiController::class, 'rekap'])->name('absensi.rekap');
        //Route::get('rapor', [App\Http\Controllers\Pendidikan\RaporController::class, 'index'])->name('rapor.index');
        //Route::post('rapor/generate', [App\Http\Controllers\Pendidikan\RaporController::class, 'generate'])->name('rapor.generate');
    });

// 2. AREA USTADZ (Mobile Interface)
// Diakses via HP (Tampilan Simpel)
Route::middleware(['auth', 'cek.langganan', 'isPremium', 'role:ustadz'])
    ->prefix('ustadz-area')
    ->name('ustadz.')
    ->group(function () {
        
        Route::get('/dashboard', [App\Http\Controllers\Ustadz\DashboardController::class, 'index'])->name('dashboard');
        
        // Menu Jadwal Mengajar & Absensi
        Route::get('/jadwal', [App\Http\Controllers\Ustadz\JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{jadwal}/absen', [App\Http\Controllers\Ustadz\AbsensiController::class, 'create'])->name('absensi.create'); // Halaman Scan/Input
        Route::post('/jadwal/{jadwal}/absen', [App\Http\Controllers\Ustadz\AbsensiController::class, 'store'])->name('absensi.store'); // Proses Simpan
        
        // Menu Jurnal & Penilaian
        Route::resource('jurnal', App\Http\Controllers\Ustadz\JurnalController::class);

        // Menu Sesi (Grid)
Route::get('/jadwal/{jadwal}/menu', [App\Http\Controllers\Ustadz\AbsensiController::class, 'showMenu'])
    ->name('absensi.menu');

// Halaman Absensi (Create & Store)
Route::get('/jadwal/{jadwal}/absensi', [App\Http\Controllers\Ustadz\AbsensiController::class, 'create'])
    ->name('absensi.create');
Route::post('/jadwal/{jadwal}/absensi', [App\Http\Controllers\Ustadz\AbsensiController::class, 'store'])
    ->name('absensi.store');

Route::get('/jadwal/{jadwal}/jurnal-kelas', [App\Http\Controllers\Ustadz\JurnalMengajarController::class, 'create'])
    ->name('jurnal-kelas.create');
Route::post('/jadwal/{jadwal}/jurnal-kelas', [App\Http\Controllers\Ustadz\JurnalMengajarController::class, 'store'])
    ->name('jurnal-kelas.store');
// Riwayat Absensi
Route::get('/jadwal/{jadwal}/riwayat', [App\Http\Controllers\Ustadz\AbsensiController::class, 'history'])
    ->name('absensi.history');
Route::get('/jadwal/{jadwal}/riwayat/{tanggal}', [App\Http\Controllers\Ustadz\AbsensiController::class, 'historyDetail'])
    ->name('absensi.history.detail');
        
// Menu Ujian (Pengawas)
Route::get('ujian', [App\Http\Controllers\Ustadz\UjianController::class, 'index'])->name('ujian.index');
Route::get('ujian/{id}', [App\Http\Controllers\Ustadz\UjianController::class, 'show'])->name('ujian.show');
Route::post('ujian/{id}/absensi', [App\Http\Controllers\Ustadz\UjianController::class, 'storeAbsensi'])->name('ujian.absensi');
Route::post('ujian/{id}/nilai', [App\Http\Controllers\Ustadz\UjianController::class, 'storeNilai'])->name('ujian.nilai');
        // Profil Ustadz
        Route::get('/profil', [App\Http\Controllers\Ustadz\DashboardController::class, 'profil'])->name('profil');
    });
