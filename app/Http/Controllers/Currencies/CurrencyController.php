<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Http\Controllers\Currencies\Populator\CurrencyPopulator;
use App\Http\Controllers\Currencies\Viewer\CurrencyViewer;

class CurrencyController extends Controller
{
    /**
     * Show all currencies.
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new CurrencyViewer())->showAll();
    }

    /**
     * Show the profile for a given currency.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        /* Validated in Viewer */
        return (new CurrencyViewer())->showByIdentifier(
            identifier: $identifier
        );
    }

    /**
     * Creates all required currencies.
     *
     * @return void
     */
    public function populate(): void
    {
        (new CurrencyPopulator())->populate();
        return;
    }
}
