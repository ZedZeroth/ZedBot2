<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Support\Facades\Artisan;

class InitializeModelsCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'models:init {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Runs all the model creation commands.';

    /**
     * Executes the command via the
     * ExceptionCatcher and CommandInformer.
     *
     */
    public function handle(): void
    {
        (new ExceptionCatcher())->catch(command: $this);
    }

    /**
     *
     * Execute the command itself.
     *
     */
    public function runThisCommand(): bool
    {
        // Run the initialization commands
        Artisan::call('migrate:refresh');
        Artisan::call('currencies:populate cli');
        Artisan::call('customers:import cli');
        Artisan::call('accounts:sync cli ENM0 100');
        Artisan::call('accounts:sync cli LCS0 100');
        Artisan::call('accounts:sync cli MMP0 100');
        Artisan::call('payments:sync cli ENM0 100');
        Artisan::call('payments:sync cli MMP0 100');
        return true;
    }
}
