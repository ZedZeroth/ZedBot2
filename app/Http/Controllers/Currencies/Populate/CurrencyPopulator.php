<?php

declare(strict_types=1);

namespace App\Http\Controllers\Currencies\Populate;

use App\Models\Currency;
use App\Http\Controllers\Currencies\CurrencyDTO;

class CurrencyPopulator
{
    /**
     * Creates all required currencies.
     *
     * @return void
     */
    public function populate(): bool
    {
        $currencyDTOs = [];

        /* USD */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'USD',
                symbol: 'US$',
                nameSingular: 'US dollar',
                namePlural: 'US dollars',
                baseUnitNameSingular: 'US cent',
                baseUnitNamePlural: 'US cents',
                decimalPlaces: 2
            )
        );

        /* GBP */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'GBP',
                symbol: '£',
                nameSingular: 'pound',
                namePlural: 'pounds',
                baseUnitNameSingular: 'penny',
                baseUnitNamePlural: 'pence',
                decimalPlaces: 2
            )
        );

        /* EUR */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'EUR',
                symbol: '€',
                nameSingular: 'euro',
                namePlural: 'euros',
                baseUnitNameSingular: 'cent',
                baseUnitNamePlural: 'cents',
                decimalPlaces: 2
            )
        );

        /* CAD */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'CAD',
                symbol: 'C$',
                nameSingular: 'Canadian dollar',
                namePlural: 'Canadian dollars',
                baseUnitNameSingular: 'Canadian cent',
                baseUnitNamePlural: 'Canadian cents',
                decimalPlaces: 2
            )
        );

        /* AUD */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'AUD',
                symbol: 'A$',
                nameSingular: 'Australian dollar',
                namePlural: 'Australian dollars',
                baseUnitNameSingular: 'Australian cent',
                baseUnitNamePlural: 'Australian cents',
                decimalPlaces: 2
            )
        );

        /* JPY */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'JPY',
                symbol: '¥',
                nameSingular: 'yen',
                namePlural: 'yen',
                baseUnitNameSingular: 'yen',
                baseUnitNamePlural: 'yen',
                decimalPlaces: 0
            )
        );

        /* CNY */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'CNY',
                symbol: '元',
                nameSingular: 'yuan',
                namePlural: 'yuan',
                baseUnitNameSingular: 'fen',
                baseUnitNamePlural: 'fen',
                decimalPlaces: 2
            )
        );

        /* BTC */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'BTC',
                symbol: '₿',
                nameSingular: 'bitcoin',
                namePlural: 'bitcoin',
                baseUnitNameSingular: 'sat',
                baseUnitNamePlural: 'sats',
                decimalPlaces: 8
            )
        );

        /* ETH */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'ETH',
                symbol: 'Ξ',
                nameSingular: 'ether',
                namePlural: 'ether',
                baseUnitNameSingular: 'wei',
                baseUnitNamePlural: 'wei',
                decimalPlaces: 18
            )
        );

        /* USDT-ERC20 */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'USDT-ERC20',
                symbol: '₮ᵉʳᶜ²⁰',
                nameSingular: 'US-Tether (ERC20)',
                namePlural: 'US-Tethers (ERC20)',
                baseUnitNameSingular: 'micro-US-Tether (ERC20)',
                baseUnitNamePlural: 'micro-US-Tethers (ERC20)',
                decimalPlaces: 6
            )
        );

        /* USDT-TRC20 */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'USDT-TRC20',
                symbol: '₮ᵗʳᶜ²⁰',
                nameSingular: 'US-Tether (TRC20)',
                namePlural: 'US-Tethers (TRC20)',
                baseUnitNameSingular: 'micro-US-Tether (TRC20)',
                baseUnitNamePlural: 'micro-US-Tethers (TRC20)',
                decimalPlaces: 6
            )
        );

        /* TRX */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'TRX',
                symbol: 'TRX',
                nameSingular: 'TRONIX',
                namePlural: 'TRONIX',
                baseUnitNameSingular: 'micro-TRONIX',
                baseUnitNamePlural: 'micro-TRONIX',
                decimalPlaces: 6
            )
        );

        /* BNB-BSC */
        array_push(
            $currencyDTOs,
            new CurrencyDTO(
                code: 'BNB-BSC',
                symbol: 'BNB',
                nameSingular: 'Binance Coin',
                namePlural: 'Binance Coin',
                baseUnitNameSingular: 'wei',
                baseUnitNamePlural: 'wei',
                decimalPlaces: 16
            )
        );

        // Validate and create each currency model
        foreach ($currencyDTOs as $currencyDTO) {
            (new \App\Http\Controllers\Currencies\Update\CurrencyUpdater())
                ->update($currencyDTO);
        }

        // Refresh the web component
        (new \App\Http\Livewire\CurrencyPopulatorComponent())->render();

        return true;
    }
}
