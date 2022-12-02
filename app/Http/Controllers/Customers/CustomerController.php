<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers;

use Illuminate\View\View;
use App\Http\Controllers\Customers\View\CustomerViewer;

class CustomerController extends \App\Http\Controllers\Controller
{
    /**
     * Show all customers.
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new CustomerViewer())->showAll();
    }

    /**
     * Show the profile for a given customer.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        /* Validated in Viewer */
        return (new CustomerViewer())->showByIdentifier(
            identifier: $identifier
        );
    }

    /**
     * Imports customer data from a CSV file,
     * creates new customers and updates
     * existing ones.
     *
     * @return true
     */
    public function import(): bool
    {
        // ↖️ Creat customers from the CustomerDTOs
        return (new \App\Http\Controllers\Customers\Import\CustomerImporter())
            ->import([]/*
                modelDTOs:
                // ↖️ Array of AccountDTOs
                (new \App\Http\Controllers\MultiDomain\Requests\Requester())
                    ->request(
                        adapterDTO:
                            // ↖️ AdapterDTO
                            (new \App\Http\Controllers\MultiDomain\Requests\AdapterBuilder())
                                ->build(
                                    model: 'Account',
                                    action: 'Synchronize',
                                    api: $syncCommandDTO->api
                                ),
                        numberToFetch: $syncCommandDTO->numberToFetch
                    )
            */);
    }
}
