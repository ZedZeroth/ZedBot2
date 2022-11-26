<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Accounts\AccountViewer;
use App\Console\Commands\SyncCommandDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\MultiDomain\Adapters\AdapterBuilder;
use App\Http\Controllers\MultiDomain\Adapters\Requester;
use App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface;

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
     * Fetches accounts from external providers
     * and creates any new ones that do not exist.
     *
     * @param SyncCommandDTO $syncCommandDTO
     * @return void
     */
    public function sync(
        SyncCommandDTO $syncCommandDTO
    ): void {
        // ↖️ Creat accounts from the AccountDTOs
        (new AccountSynchronizer())
            ->sync(
                // ↖️ Array of AccountDTOs
                (new Requester())->request(
                    adapterDTO:
                        // ↖️ AdapterDTO
                        (new AdapterBuilder())->build(
                            models: 'Accounts',
                            action: 'Synchronizer',
                            provider: $syncCommandDTO->provider
                        ),
                    numberToFetch: $syncCommandDTO->numberToFetch
                )
            );
        return;
    }
}
