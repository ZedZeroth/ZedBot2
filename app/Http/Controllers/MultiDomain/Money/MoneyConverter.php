<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Money;

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
        \App\Models\Currency $currency
    ): int {

        $inBaseDenomination = $amount * pow(10, $currency->decimalPlaces);

        if ($inBaseDenomination != round($inBaseDenomination)) {
            throw new \Exception('"' . $inBaseDenomination . '" should be an integer after conversion');
        }

        return (int) $inBaseDenomination; 
    }
}
