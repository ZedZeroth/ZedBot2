<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies;

class CurrencyDTO implements
    \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface
{
    /**
     * The currency data transfer object
     * for moving currency data between
     * an adapter and the synchronizer.
     */
    public function __construct(
        public string $code,
        public string $symbol,
        public string $nameSingular,
        public string $namePlural,
        public string $baseUnitNameSingular,
        public string $baseUnitNamePlural,
        public int $decimalPlaces,
    ) {
    }
}
