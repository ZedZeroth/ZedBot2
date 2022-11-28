<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MultiDomain\Validators\StringValidator;

class SyncAccountsCommand extends Command
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
        'Synchronizes the account table with accounts from account providers.';

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
        (new StringValidator())->validate(
            string: $this->argument('API'),
            stringName: 'API',
            shortestLength: 3,
            longestLength: 4,
            containsUppercase: true,
            containsLowercase: false,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Build the DTO
        $syncCommandDTO = new SyncCommandDTO(
            api: $this->argument('API'),
            numberToFetch: (int) $this->argument('Number to fetch')
        );

        // Inject the DTO into the relevant controller method
        (new AccountController())
            ->sync(syncCommandDTO: $syncCommandDTO);

        return;
    }
}
