<?php

declare(strict_types=1);

namespace App\Console\Commands;

class ImportCustomersCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'customers:import {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Imports customers from a CSV file.';

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
        (new \App\Http\Controllers\Customers\CustomerController())
            ->import();
        return true;
    }
}
