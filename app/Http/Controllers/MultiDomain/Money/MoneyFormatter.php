<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Money;

use App\Models\Currency;

class MoneyFormatter
{
    /**
     * Formats money from base units into
     * a string in its usual denomination.
     *
     * @param int $amount
     * @param Currency $currency
     * @return string
     */
    public function format(
        int $amount,
        Currency $currency
    ): string {
        return number_format(
            $amount / pow(10, $currency->decimalPlaces),
            $currency->decimalPlaces,
            '.',
            ',',
        );
    }
}
