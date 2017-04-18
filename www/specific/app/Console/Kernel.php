<?php

namespace App\Console;

use App\Console\Commands\ImportAll;
use App\Console\Commands\ImportAnnotations;
use App\Console\Commands\ImportDiseases;
use App\Console\Commands\ImportPathways;
use App\Console\Commands\IndexPathways;
use App\Console\Commands\RunFailed;
use App\Console\Commands\TestJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ImportAll::class,
        IndexPathways::class,
        ImportPathways::class,
        ImportDiseases::class,
        ImportAnnotations::class,
        TestJob::class,
        RunFailed::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
