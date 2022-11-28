<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Console\Commands\SyncCommandDTO;
use App\Http\Controllers\Accounts\Viewer\AccountViewer;
use App\Http\Controllers\Accounts\Synchronizer\AccountSynchronizer;
use App\Http\Controllers\MultiDomain\Adapters\AdapterBuilder;
use App\Http\Controllers\MultiDomain\Adapters\Requester;
use App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface;
use App\Http\Controllers\MultiDomain\Validators\APIValidator;
use App\Http\Controllers\MultiDomain\Validators\DTOValidator;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidator;

class AccountController extends Controller implements
    ControllerInterface
{
    /**
     * Show all accounts (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new AccountViewer())->showAll();
    }

    /**
     * Show the profile for a specific account.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return (new AccountViewer())
            ->showByIdentifier(
                identifier: $identifier
            );
    }

    /**
     * Show all account networks.
     *
     * @return View
     */
    public function showNetworks(): View
    {
        return (new AccountViewer())->showNetworks();
    }

    /**
     * Show all accounts on one account network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View {
        return (new AccountViewer())
            ->showOnNetwork(
                network: $network
            );
    }

    /**
     * Fetches accounts from external APIs
     * and creates any new ones that do not exist.
     *
     * @param SyncCommandDTO $syncCommandDTO
     * @return void
     */
    public function sync(
        SyncCommandDTO $syncCommandDTO
    ): void {

        // Validate DTO property names
        (new DTOValidator())->validate(
            dto: $syncCommandDTO,
            dtoName: 'syncCommandDTO',
            requiredProperties: ['api','numberToFetch']
        );

        // Validate API code
        (new APIValidator())->validate(apiCode: $syncCommandDTO->api);

        // Validate number to fetch
        (new IntegerValidator())->validate(
            integer: $syncCommandDTO->numberToFetch,
            integerName: 'Number to fetch',
            lowestValue: 1,
            highestValue: pow(10, 5)
        );

        // ↖️ Creat accounts from the AccountDTOs
        (new AccountSynchronizer())
            ->sync(
                // ↖️ Array of AccountDTOs
                (new Requester())->request(
                    adapterDTO:
                        // ↖️ AdapterDTO
                        (new AdapterBuilder())->build(
                            model: 'Account',
                            action: 'Synchronizer',
                            api: $syncCommandDTO->api
                        ),
                    numberToFetch: $syncCommandDTO->numberToFetch
                )
            );
        return;
    }
}
