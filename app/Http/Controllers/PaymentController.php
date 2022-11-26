<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Payments\PaymentViewer;
use App\Console\Commands\SyncCommandDTO;
use App\Http\Controllers\Payments\PaymentSynchronizer;
use App\Http\Controllers\MultiDomain\Adapters\AdapterBuilder;
use App\Http\Controllers\MultiDomain\Adapters\Requester;
use App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface;

class PaymentController extends Controller implements ControllerInterface
{
    /**
     * Show all payments (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new PaymentViewer())->showAll();
    }

    /**
     * Show the profile for a specific payment.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return (new PaymentViewer())
            ->showByIdentifier(
                identifier: $identifier
            );
    }

    /**
     * Show all payment networks.
     *
     * @return View
     */
    public function showNetworks(): View
    {
        return (new PaymentViewer())->showNetworks();
    }

    /**
     * Show all payments on one payment network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View {
        return (new PaymentViewer())
            ->showOnNetwork(
                network: $network
            );
    }

    /**
     * Fetches recent payments from external providers
     * and creates any new ones that do not exist.
     *
     * @param SyncCommandDTO $syncCommandDTO
     * @return void
     */
    public function sync(
        SyncCommandDTO $syncCommandDTO
    ): void {
        // ↖️ Creat payments from the DTOs
        (new PaymentSynchronizer())
            ->sync(
                // ↖️ Build DTOs from the request
                (new Requester())->request(
                    adapterDTO:
                        // ↖️ Build the required adapters
                        (new AdapterBuilder())->build(
                            models: 'Payments',
                            action: 'Synchronizer',
                            provider: $syncCommandDTO->provider
                        ),
                    numberToFetch: $syncCommandDTO->numberToFetch
                )
            );
        return;
    }
}
