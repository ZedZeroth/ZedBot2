<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Currencies\CurrencyPopulator;

class PopulateCurrenciesCommand extends Command
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
        (new ExceptionCatcher())->catch(
            command: $this,
            class: __CLASS__,
            function: __FUNCTION__,
            line: __LINE__
        );
    }

    /**
     * Execute the command itself.
     *
     * @return void
     */
    public function runThisCommand(): void
    {
        (new CurrencyController())->populate();
        return;
    }
}
