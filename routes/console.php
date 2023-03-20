<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear', function() {
    $logs_content = scandir(storage_path('logs'));

    foreach ($logs_content as $file) {
        if (!preg_match('/^.*\.log$/', $file))
            continue;

        exec('rm ' . storage_path('logs/' . $file));
    }
    $this->comment('Logs have been cleared!');
})->describe('Clear log files');
