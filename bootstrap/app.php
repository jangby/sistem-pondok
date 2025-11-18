<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken; // <-- TAMBAHKAN INI

// Middleware Spatie Permission
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            // Hapus 'my_custom_alias' karena tidak diperlukan
            // Jika Anda memang ingin membuatnya, pastikan Anda punya alasan kuat.
        ]);

        $middleware->validateCsrfTokens(except: [
            'midtrans/webhook',
            'gate/api/scan',
        ]);

        $middleware->alias([
            
            // 1. Alias Bawaan Spatie (YANG HILANG)
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // 2. Alias Baru Kita (CEK LANGGANAN)
            'cek.langganan' => \App\Http\Middleware\CheckSubscriptionStatus::class,
            'isPremium' => \App\Http\Middleware\CheckPremiumSubscription::class,

        ]);
        
        // Hapus juga pengecekan role di route web/api bawaan
        // $middleware->web(append: [
        //    \App\Http\Middleware\Authenticate::class,
        // ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();