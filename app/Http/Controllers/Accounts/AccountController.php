<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts;

use Illuminate\View\View;
use App\Http\Controllers\Accounts\View\AccountViewer;

class AccountController extends \App\Http\Controllers\Controller implements
    \App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface
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

        /* Validated in Viewer */
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
        /* Validated in Viewer */
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
        \App\Console\Commands\SyncCommandDTO $syncCommandDTO
    ): void {

        // Validate DTO property names
        (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
            ->validate(
                dto: $syncCommandDTO,
                dtoName: 'syncCommandDTO',
                requiredProperties: ['api','numberToFetch']
            );

        // Validate API code
        (new \App\Http\Controllers\MultiDomain\Validators\ApiValidator())
            ->validate(apiCode: $syncCommandDTO->api);

        // Validate number to fetch
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())
            ->validate(
                integer: $syncCommandDTO->numberToFetch,
                integerName: 'Number to fetch',
                lowestValue: 1,
                highestValue: pow(10, 5)
            );

        // ?????? Creat accounts from the AccountDTOs
        (new \App\Http\Controllers\Accounts\Synchronize\AccountSynchronizer())
            ->sync(
                modelDTOs:
                // ?????? Array of AccountDTOs
                (new \App\Http\Controllers\MultiDomain\Requests\Requester())
                    ->request(
                        adapterDTO:
                            // ?????? AdapterDTO
                            (new \App\Http\Controllers\MultiDomain\Requests\AdapterBuilder())
                                ->build(
                                    model: 'Account',
                                    action: 'Synchronize',
                                    api: $syncCommandDTO->api
                                ),
                        numberToFetch: $syncCommandDTO->numberToFetch
                    ),
                accountUpdater:
                    (new \App\Http\Controllers\Accounts\Update\AccountUpdater())
            );
        return;
    }
}
