<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies\Populate;

use App\Models\Currency;

class CurrencyPopulator
{
    /**
     * Creates all required currencies.
     *
     * @return void
     */
    public function populate(): void
    {
        $currencies = [];

        /* USD */
        array_push(
            $currencies,
            [
                'code' => 'USD',
                'symbol' => 'US$',
                'nameSingular' => 'US dollar',
                'namePlural' => 'US dollars',
                'baseUnitNameSingular' => 'US cent',
                'baseUnitNamePlural' => 'US cents',
                'decimalPlaces' => 2
            ]
        );

        /* GBP */
        array_push(
            $currencies,
            [
                'code' => 'GBP',
                'symbol' => '£',
                'nameSingular' => 'pound',
                'namePlural' => 'pounds',
                'baseUnitNameSingular' => 'penny',
                'baseUnitNamePlural' => 'pence',
                'decimalPlaces' => 2
            ]
        );

        /* EUR */
        array_push(
            $currencies,
            [
                'code' => 'EUR',
                'symbol' => '€',
                'nameSingular' => 'euro',
                'namePlural' => 'euros',
                'baseUnitNameSingular' => 'cent',
                'baseUnitNamePlural' => 'cents',
                'decimalPlaces' => 2
            ]
        );

        /* CAD */
        array_push(
            $currencies,
            [
                'code' => 'CAD',
                'symbol' => 'C$',
                'nameSingular' => 'Canadian dollar',
                'namePlural' => 'Canadian dollars',
                'baseUnitNameSingular' => 'Canadian cent',
                'baseUnitNamePlural' => 'Canadian cents',
                'decimalPlaces' => 2
            ]
        );

        /* AUD */
        array_push(
            $currencies,
            [
                'code' => 'AUD',
                'symbol' => 'A$',
                'nameSingular' => 'Australian dollar',
                'namePlural' => 'Australian dollars',
                'baseUnitNameSingular' => 'Australian cent',
                'baseUnitNamePlural' => 'Australian cents',
                'decimalPlaces' => 2
            ]
        );

        /* JPY */
        array_push(
            $currencies,
            [
                'code' => 'JPY',
                'symbol' => '¥',
                'nameSingular' => 'yen',
                'namePlural' => 'yen',
                'baseUnitNameSingular' => 'yen',
                'baseUnitNamePlural' => 'yen',
                'decimalPlaces' => 0
            ]
        );

        /* CNY */
        array_push(
            $currencies,
            [
                'code' => 'CNY',
                'symbol' => '元',
                'nameSingular' => 'yuan',
                'namePlural' => 'yuan',
                'baseUnitNameSingular' => 'fen',
                'baseUnitNamePlural' => 'fen',
                'decimalPlaces' => 2
            ]
        );

        /* BTC */
        array_push(
            $currencies,
            [
                'code' => 'BTC',
                'symbol' => '₿',
                'nameSingular' => 'bitcoin',
                'namePlural' => 'bitcoin',
                'baseUnitNameSingular' => 'sat',
                'baseUnitNamePlural' => 'sats',
                'decimalPlaces' => 8
            ]
        );

        /* ETH */
        array_push(
            $currencies,
            [
                'code' => 'ETH',
                'symbol' => 'Ξ',
                'nameSingular' => 'ether',
                'namePlural' => 'ether',
                'baseUnitNameSingular' => 'wei',
                'baseUnitNamePlural' => 'wei',
                'decimalPlaces' => 18
            ]
        );

        /* USDT-ERC20 */
        array_push(
            $currencies,
            [
                'code' => 'USDT-ERC20',
                'symbol' => '₮ᵉʳᶜ²⁰',
                'nameSingular' => 'US-Tether (ERC20)',
                'namePlural' => 'US-Tethers (ERC20)',
                'baseUnitNameSingular' => 'micro-US-Tether (ERC20)',
                'baseUnitNamePlural' => 'micro-US-Tethers (ERC20)',
                'decimalPlaces' => 6
            ]
        );

        /* USDT-TRC20 */
        array_push(
            $currencies,
            [
                'code' => 'USDT-TRC20',
                'symbol' => '₮ᵗʳᶜ²⁰',
                'nameSingular' => 'US-Tether (TRC20)',
                'namePlural' => 'US-Tethers (TRC20)',
                'baseUnitNameSingular' => 'micro-US-Tether (TRC20)',
                'baseUnitNamePlural' => 'micro-US-Tethers (TRC20)',
                'decimalPlaces' => 6
            ]
        );

        /* TRX */
        array_push(
            $currencies,
            [
                'code' => 'TRX',
                'symbol' => 'TRX',
                'nameSingular' => 'TRONIX',
                'namePlural' => 'TRONIX',
                'baseUnitNameSingular' => 'micro-TRONIX',
                'baseUnitNamePlural' => 'micro-TRONIX',
                'decimalPlaces' => 6
            ]
        );

        /* BNB-BSC */
        array_push(
            $currencies,
            [
                'code' => 'BNB-BSC',
                'symbol' => 'BNB',
                'nameSingular' => 'Binance Coin',
                'namePlural' => 'Binance Coin',
                'baseUnitNameSingular' => 'wei',
                'baseUnitNamePlural' => 'wei',
                'decimalPlaces' => 16
            ]
        );

        // Validate and create each currency model
        foreach ($currencies as $currency) {
            // Validate code
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $currency['code'],
                stringName: 'code',
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
                string: $currency['symbol'],
                stringName: 'symbol',
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
                    string: $currency[$name],
                    stringName: $name,
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
                integer: $currency['decimalPlaces'],
                integerName: 'decimalPlaces',
                lowestValue: 0,
                highestValue: 18
            );

            // Create
            $currency = Currency::firstOrCreate(
                ['code' => $currency['code']],
                [
                    'symbol' => $currency['symbol'],
                    'nameSingular' => $currency['nameSingular'],
                    'namePlural' => $currency['namePlural'],
                    'baseUnitNameSingular' => $currency['baseUnitNameSingular'],
                    'baseUnitNamePlural' => $currency['baseUnitNamePlural'],
                    'decimalPlaces' => $currency['decimalPlaces']
                ]
            );
        }

        // Refresh the web component
        (new \App\Http\Livewire\CurrencyPopulatorComponent())->render();

        return;
    }
}
