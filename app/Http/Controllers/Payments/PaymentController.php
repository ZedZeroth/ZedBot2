<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use Illuminate\View\View;
use App\Http\Controllers\Payments\View\PaymentViewer;

class PaymentController extends \App\Http\Controllers\Controller implements
    \App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface
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
        /* Validated in Viewer */
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
        /* Validated by Viewer */
        return (new PaymentViewer())
            ->showOnNetwork(
                network: $network
            );
    }

    /**
     * Fetches recent payments from external APIs
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
                highestValue: pow(10, 6)
            );

        // ↖️ Creat payments (and linked accounts) from the DTOs
        (new \App\Http\Controllers\Payments\Synchronize\PaymentSynchronizer())
            ->sync(
                modelDTOs:
                // ↖️ Build DTOs from the request
                (new \App\Http\Controllers\MultiDomain\Requests\Requester())
                    ->request(
                        adapterDTO:
                            // ↖️ Build the required adapters
                            (new \App\Http\Controllers\MultiDomain\Requests\AdapterBuilder())
                                ->build(
                                    model: 'Payment',
                                    action: 'Synchronize',
                                    api: $syncCommandDTO->api
                                ),
                        numberToFetch: $syncCommandDTO->numberToFetch
                    ),
                accountSynchronizer:
                    (new \App\Http\Controllers\Accounts\Synchronize\AccountSynchronizer())
            );
        return;
    }
}
