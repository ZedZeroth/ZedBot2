<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\View;

use Illuminate\View\View;
use App\Models\Customer;
use App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder;
use App\Http\Controllers\MultiDomain\Html\HtmlAccountRowBuilder;

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
        $customers = Customer::all()
            ->sortBy('givenName2')
            ->sortBy('givenName1')
            ->sortBy('familyName');

        return view('customers', [
            'customers' => $customers
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

        // Build accounts table
        if ($customer->accounts()->count()) {
            $accountsTable =
                (new HtmlAccountRowBuilder())
                    ->build($customer->accounts()->get());
        } else {
            $accountsTable = 'No accounts exist.';
        }

        // Build payment tables
        $creditsTable = 'No credits exist.';
        $debitsTable = 'No debits exist.';
        $credits = collect();
        $debits = collect();
        foreach ($customer->accounts()->get() as $account) {
            $credits = $credits->merge($account->credits()->get());
            $debits = $debits->merge($account->debits()->get());
        }

        // Build credits table
        if ($credits->count()) {
            $creditsTable =
                (new HtmlPaymentRowBuilder())
                    ->build($credits);
        }

        // Build debits table
        if ($debits->count()) {
            $debitsTable =
                (new HtmlPaymentRowBuilder())
                    ->build($debits);
        }

        // Return the View
        return view('customer', [
            'customer'      => $customer,
            'modelTable'    =>
                (new \App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder())
                    ->build($customer),
            'accountsTable' => $accountsTable,
            'creditsTable'  => $creditsTable,
            'debitsTable'   => $debitsTable
        ]);
    }
}
