<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Custom artisan commands
     */
    protected $commands = [

        Commands\PopulateCurrenciesCommand::class,
        Commands\SchedulerIsRunningCommand::class,
        Commands\SyncAccountsCommand::class,
        Commands\SyncPaymentsCommand::class,
        Commands\ImportCustomersCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('schedule:running')->everyMinute();//->appendOutputTo('zedlog');
        $schedule->command('currencies:populate')->everyMinute();//->appendOutputTo('zedlog');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
