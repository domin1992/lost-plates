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

Artisan::command('test-mail {email}', function ($email) {
    \Mail::to($email)->send(new \App\Mail\Test);
    \Mail::to($email)->queue(new \App\Mail\Test);
});

Artisan::command('monitor-queue-listener', function(){
    $run_command = false;
    if (file_exists(storage_path('app/queue.pid'))) {
        $pid = file_get_contents(storage_path('app/queue.pid'));
        $result = exec("ps -p $pid --no-heading | awk '{print $1}'");
        if (!$result) {
            $run_command = true;
        }
    } else {
        $run_command = true;
    }

    if ($run_command) {
        $command = 'php artisan queue:listen > /dev/null & echo $!';
        $number = exec($command);
        file_put_contents(storage_path('app/queue.pid'), $number);
    }
});

Artisan::command('monitor-queue-reset', function(){
    if (file_exists(storage_path('app/queue.pid'))) {
        $pid = file_get_contents(storage_path('app/queue.pid'));
        exec("kill -9 $pid");
        @unlink(storage_path('app/queue.pid'));
    }
});