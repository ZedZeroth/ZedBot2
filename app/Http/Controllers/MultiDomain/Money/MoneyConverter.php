<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Money;

use App\Models\Currency;

class MoneyConverter
{
    /**
     * Converts money from its usual denomination
     * into its base units.
     *
     * @param float $amount
     * @param Currency $currency
     * @return int
     */
    public function convert(
        float $amount,
        Currency $currency
    ): int {
        return $amount * pow(10, $currency->decimalPlaces);
    }
}
