<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies\Update;

use App\Models\Currency;

class CurrencyUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update payment models.
     *
     * @param ModelDtoInterface $modelDTO
     */
    public function update(
        \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface $modelDTO
    ): \Illuminate\Database\Eloquent\Model {
        // Validate code
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->code,
            stringName: '$modelDTO->code',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: ['-'],
            shortestLength: 3,
            longestLength: 10,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate symbol
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->symbol,
            stringName: '$modelDTO->symbol',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 1,
            longestLength: 16,
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: false,
            isHexadecimal: false
        );

        // Validate names
        foreach (
            [
                'nameSingular',
                'namePlural',
                'baseUnitNameSingular',
                'baseUnitNamePlural'
            ] as $name
        ) {
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $modelDTO->$name,
                stringName: '$modelDTO->' . $name,
                source: __FILE__ . ' (' . __LINE__ . ')',
                charactersToRemove: [' ', '-', '(', ')'],
                shortestLength: 3,
                longestLength: pow(10, 2),
                mustHaveUppercase: false,
                canHaveUppercase: true,
                mustHaveLowercase: false,
                canHaveLowercase: true,
                isAlphabetical: false,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );
        }

        // Validate decimalPlaces
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $modelDTO->decimalPlaces,
            integerName: '$modelDTO->decimalPlaces',
            lowestValue: 0,
            highestValue: 18
        );

        // Create
        $currency = Currency::firstOrCreate(
            ['code' => $modelDTO->code],
            [
                'symbol'                => $modelDTO->symbol,
                'nameSingular'          => $modelDTO->nameSingular,
                'namePlural'            => $modelDTO->namePlural,
                'baseUnitNameSingular'  => $modelDTO->baseUnitNameSingular,
                'baseUnitNamePlural'    => $modelDTO->baseUnitNamePlural,
                'decimalPlaces'         => $modelDTO->decimalPlaces
            ]
        );

        return $currency;
    }
}
