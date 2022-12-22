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

        Commands\SchedulerIsRunningCommand::class,
        Commands\InitializeModelsCommand::class,
        Commands\PopulateCurrenciesCommand::class,
        Commands\ImportCustomersCommand::class,
        Commands\SyncAccountsCommand::class,
        Commands\SyncPaymentsCommand::class,
        Commands\AssessCustomersCommand::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('schedule:running start')->everyMinute();

        // everyTenMinutes
        $schedule->command('payments:sync scheduler ENM0 1000')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // everyMinute
        $schedule->command('payments:sync scheduler ENM0 10')
            ->everyMinute()
            ->withoutOverlapping();
        $schedule->command('payments:sync scheduler MMP0 10')
            ->everyMinute()
            ->withoutOverlapping();
        $schedule->command('payments:sync scheduler TRS0 10')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('schedule:running stop')->everyMinute();
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
