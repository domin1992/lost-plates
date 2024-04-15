<?php

namespace App\Console;

use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\GeocodeMarker;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('model:prune')->everyThreeHours();
        $schedule->command(GeocodeMarker::class)->everyFifteenMinutes();
        // $schedule->command(GenerateSitemap::class)->daily();

        if (app()->isProduction()) {
            $schedule->command('monitor-queue-listener')->everyFiveMinutes();
            $schedule->command('monitor-queue-reset')->twiceDaily(3, 19);
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
