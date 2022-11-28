<?php

declare(strict_types=1);

namespace App\Console\Commands;

class SyncPaymentsCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'payments:sync {source} {API} {Number to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Synchronizes the payment table with new payments from payment networks.';

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
     */
    public function runThisCommand(): void
    {
        /* Validated by ExceptionCatcher */

        // Build the DTO
        $syncCommandDTO = new SyncCommandDTO(
            api: $this->argument('API'),
            numberToFetch: (int) $this->argument('Number to fetch')
        );

        // Inject the DTO into the relevant controller method
        (new \App\Http\Controllers\Payments\PaymentController())
            ->sync(syncCommandDTO: $syncCommandDTO);

        return;
    }
}
