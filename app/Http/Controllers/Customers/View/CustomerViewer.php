<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\View;

use Illuminate\View\View;
use App\Models\Customer;

class CustomerViewer implements
    \App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface
{
    /**
     * Show all customers.
     *
     * @return View
     */
    public function showAll(): View
    {
        // TESTING
        $customerDTO = new \App\Http\Controllers\Customers\CustomerDTO(
            state: 'test',
            identifier: 'test::test',
            type: 'test',
            familyName: 'Test',
            givenName1: 'Test',
            givenName2: 'Test',
            companyName: 'Test Ltd',
            preferredName: 'T',
        );

        (new \App\Http\Controllers\Customers\Import\CustomerImporter())->import(
            modelDTOs: [$customerDTO]
        );

        return view('customers', [
            'customers' => Customer::all()
        ]);
    }

    /**
     * Show the profile for a specific customer.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {

        // Verify customer exists
        $customer = Customer::where('identifier', $identifier)->firstOrFail();

        // Return the View
        return view('customer', [
            'customer' => $customer,
            'modelTable' =>
                (new \App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder())
                    ->build($customer),
        ]);
    }
}
