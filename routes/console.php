<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('gate:check')->everyMinute();
Schedule::command('absensi:check-guru-notifications')->everyMinute();
Schedule::command('nilai:check-completion')->hourly();
