<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Enviar estadísticas semanales todos los lunes a las 9:00 AM
Schedule::command('stats:send-weekly')->weeklyOn(1, '9:00');
