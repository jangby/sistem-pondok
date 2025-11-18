<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Channels\WahaChannel;
use Illuminate\Foundation\AliasLoader;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- INI PERBAIKANNYA ---
        // Daftarkan WahaChannel kita dengan nama alias 'waha'
        Notification::extend('waha', function ($app) {
            return new WahaChannel();
        });
        // ------------------------
    }
}
