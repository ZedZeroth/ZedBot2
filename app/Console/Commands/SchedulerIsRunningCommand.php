<?php

declare(strict_types=1);

namespace App\Console\Commands;

class SchedulerIsRunningCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'schedule:running {start_stop}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Tells the CLI/log that the scheduler is running.';

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        /* Output messages */
        if ($this->argument('start_stop') == 'start') {
            $output = 'The scheduler is starting ...';
        } else {
            $output = 'The scheduler is stopping ...';
        }
        $this->info($output);
        \Illuminate\Support\Facades\Log::info($output);
    }
}
