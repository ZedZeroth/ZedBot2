<?php

declare(strict_types=1);

namespace App\Console\Commands;

class AssessCustomersCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'customers:assess {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Generates full risk assessments for each customer.';

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
        return (new \App\Http\Controllers\Customers\CustomerController())
            ->assess();
    }
}
