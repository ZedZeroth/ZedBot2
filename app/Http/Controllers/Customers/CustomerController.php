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
            ->import(
                modelDTOs:
                // ↖️ Array of CustomerDTOs
                (new \App\Http\Controllers\MultiDomain\Imports\Importer())
                    ->import(
                        readerArray:
                            (new \App\Http\Controllers\MultiDomain\Imports\CsvReader())
                                ->read('customer_records.csv'),
                        importerAdapter:
                            (new \App\Http\Controllers\Customers\Import\CustomerImportAdapterForCSV())
                    ),
                customerUpdater:
                    (new \App\Http\Controllers\Customers\Update\CustomerUpdater()),
                accountUpdater:
                    (new \App\Http\Controllers\Accounts\Update\AccountUpdater())
            );
    }
}
