<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronize\Response;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class AccountSynchronizeResponseAdapterForLCS0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Builds an array of model DTOs
     * from the responseArray.
     *
     * @param array $responseArray
     * @return array
     */
    public function buildDTOs(
        array $responseArray
    ): array {
        /*💬*/ //print_r($responseArray);

        // Validate the injected array
        (new ArrayValidator())->validate(
            array: $responseArray,
            arrayName: 'responseArray',
            requiredKeys: [
                'currency',
                'wallets',
                'currency_balance',
                'local_currency_symbol',
                'unit_amount_in_local_currency',
                'nc_currency',
                'fee_currency',
                'gas_price',
                'network_fees',
                'fees_info',
                'nc_currency_balance',
                'nc_unit_amount_in_local_currency',
                'nc_wallets',
                'hdaddresses'
            ],
            keysToIgnore: []
        );

        // Validate $responseArray['currency_balance']['total_balance']
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: (int) round(pow(10, 8) * $responseArray['currency_balance']['total_balance']),
            integerName: 'total_balance',
            lowestValue: 0,
            highestValue: pow(10, 9)
        );

        //Adapt account

        // Determine the currency
        $currency = \App\Models\Currency::
        where(
            'code',
            'BTC'
        )->firstOrFail();

        // Convert amount to base units
        $balance = (new \App\Http\Controllers\MultiDomain\Money\MoneyConverter())
        ->convert(
            amount: $responseArray['currency_balance']['total_balance'],
            currency: $currency
        );

        // Unique labeling
        $username = config('app.ZED_LCS0_USERNAME');
        $walletType = 'CustodialWallet';

        // Build DTO
        $accountDTO = new \App\Http\Controllers\Accounts\AccountDTO(
            network: (string) 'LCS',
            identifier: (string) 'lcs'
                . '::' . strtolower($currency->code)
                . '::' . $username
                . '::' . $walletType,
            customer_id: (int) 0,
            networkAccountName: '',
            label: (string) $username . ' ' . $currency->code . ' ' . $walletType,
            currency_id: (int) $currency->id,
            balance: (int) $balance,
        );

        return [$accountDTO];
    }
}
