<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies\Viewer;

use Illuminate\View\View;
use App\Models\Currency;

class CurrencyViewer implements
    \App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface
{
    /**
     * Show all currencies.
     *
     * @return View
     */
    public function showAll(): View
    {
        return view('currencies', [
            'currencies' => Currency::all()
        ]);
    }

    /**
     * Show the profile for a specific currency.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {

        // Verify currency exists
        $currency = Currency::where('code', $identifier)->firstOrFail();

        // Return the View
        return view('currency', [
            'currency' => $currency,
            'modelTable' =>
                (new \App\Http\Controllers\MultiDomain\Html\HtmlModelTableBuilder())
                    ->build($currency),
            'paymentsTable' =>
                (new \App\Http\Controllers\MultiDomain\Html\HtmlPaymentRowBuilder())
                    ->build($currency->payments()->get()),
            'moneyConverter'
                => new \App\Http\Controllers\MultiDomain\Money\MoneyConverter()
        ]);
    }
}
