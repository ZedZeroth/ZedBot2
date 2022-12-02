<?php

declare(strict_types=1);

namespace App\Console\Commands;

class PopulateCurrenciesCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public /* Do not define */ $signature =
        'currencies:populate {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Creates all required currencies.';

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
     * Execute the command itself.
     *
     * @return void
     */
    public function runThisCommand(): void
    {
        /* Validated by ExceptionCatcher */

        (new \App\Http\Controllers\Currencies\CurrencyController())
            ->populate();
        return;
    }
}
