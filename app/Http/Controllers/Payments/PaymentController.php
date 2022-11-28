<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Console\Commands\SyncCommandDTO;
use App\Http\Controllers\Payments\Viewer\PaymentViewer;
use App\Http\Controllers\Payments\Synchronizer\PaymentSynchronizer;
use App\Http\Controllers\MultiDomain\Adapters\AdapterBuilder;
use App\Http\Controllers\MultiDomain\Adapters\Requester;
use App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface;
use App\Http\Controllers\MultiDomain\Validators\APIValidator;
use App\Http\Controllers\MultiDomain\Validators\DTOValidator;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidator;

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
     * Fetches recent payments from external APIs
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
            highestValue: pow(10, 6)
        );

        // ↖️ Creat payments from the DTOs
        (new PaymentSynchronizer())
            ->sync(
                // ↖️ Build DTOs from the request
                (new Requester())->request(
                    adapterDTO:
                        // ↖️ Build the required adapters
                        (new AdapterBuilder())->build(
                            model: 'Payment',
                            action: 'Synchronizer',
                            api: $syncCommandDTO->api
                        ),
                    numberToFetch: $syncCommandDTO->numberToFetch
                )
            );
        return;
    }
}
