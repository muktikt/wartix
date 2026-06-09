<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('wartix:retry-failed')->hourly();
Schedule::command('queue:prune-failed', ['--hours' => 48])->daily();
Schedule::command('wartix:warm-cache')->everyFiveMinutes();
Schedule::command('wartix:check-expired-payments')->everyMinute();
