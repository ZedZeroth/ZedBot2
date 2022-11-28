<?php

declare(strict_types=1);

namespace App\Console\Commands;

class SyncAccountsCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'accounts:sync {source} {API} {Number to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Synchronizes the account table with accounts from payment networks.';

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
     *
     * Execute the command itself.
     *
     */
    public function runThisCommand(): void
    {
        // Validate the command arguments
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $this->argument('API'),
            stringName: 'API',
            shortestLength: 4,
            longestLength: 4,
            containsUppercase: true,
            containsLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: (int) $this->argument('Number to fetch'),
            integerName: 'Number to fetch',
            lowestValue: 1,
            highestValue: pow(10, 6)
        );

        // Build the DTO
        $syncCommandDTO = new SyncCommandDTO(
            api: $this->argument('API'),
            numberToFetch: (int) $this->argument('Number to fetch')
        );

        // Inject the DTO into the relevant controller method
        (new \App\Http\Controllers\Accounts\AccountController())
            ->sync(syncCommandDTO: $syncCommandDTO);

        return;
    }
}
