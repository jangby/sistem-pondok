<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; // Tambahkan facade Route

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Gunakan parameter 'then' untuk mendaftarkan rute tambahan
        then: function () {
            // Helper function agar tidak mengulang penulisan middleware 'web'
            $registerRoute = function ($file) {
                Route::middleware('web')
                    ->group(base_path("routes/{$file}"));
            };

            $registerRoute('superadmin.php');
            $registerRoute('admin_pondok.php');
            $registerRoute('orangtua.php');
            $registerRoute('bendahara.php');
            $registerRoute('uang_jajan.php');
            $registerRoute('pengurus.php');
            $registerRoute('sekolah.php');
            $registerRoute('pesantren.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ... (Kode middleware Anda tetap sama, tidak ada perubahan di sini)
        
        $middleware->trustProxies(at: [
            '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
            '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
            '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
            '104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22'
        ]);

        $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB
        );

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'cek.langganan' => \App\Http\Middleware\CheckSubscriptionStatus::class,
            'isPremium' => \App\Http\Middleware\CheckPremiumSubscription::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans/webhook',
            'gate/api/scan',
            'gerbang/api/scan', // Menambahkan route scan yang ada di web.php
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();