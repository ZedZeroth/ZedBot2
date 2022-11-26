<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies;

use Illuminate\View\View;
use App\Http\Controllers\MultiDomain\Interfaces\ViewerInterface;
use App\Models\Currency;
use App\Http\Controllers\Html\HtmlModelTableBuilder;
use App\Http\Controllers\Html\HtmlPaymentRowBuilder;
use App\Http\Controllers\MultiDomain\Money\MoneyConverter;

class CurrencyViewer implements ViewerInterface
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
        $currency = Currency::where('code', $identifier)->firstOrFail();
        return view('currency', [
            'currency' => $currency,
            'modelTable' =>
                (new HtmlModelTableBuilder())
                    ->build($currency),
            'paymentsTable' =>
                (new HtmlPaymentRowBuilder())
                    ->build($currency->payments()->get()),
            'moneyConverter' => new MoneyConverter()
        ]);
    }
}
