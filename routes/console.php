<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\IuranService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $tahunDepan = now()->year + 1;
    app(IuranService::class)->generate($tahunDepan);
})->yearlyOn(12, 25, '00:00');

// Schedule::call(function () {
//     $tahunDepan = now()->year + 1;
//     app(IuranService::class)->generate($tahunDepan);
// })->everyMinute();
