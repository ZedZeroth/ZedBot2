<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Responses;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class AccountSynchronizerResponseAdapterForLCS0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Builds an array of model DTOs
     * from the responseBody.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array {
        /*💬*/ //print_r($responseBody);

        // Validate the injected array
        (new ArrayValidator())->validate(
            array: $responseBody,
            arrayName: 'responseBody',
            requiredKeys: ['count', 'results']
        );

        //Adapt accounts
        $accountDTOs = [];
        $walletsWithBalance = [];
        foreach (
            $responseBody['currencies'] as $currencyCode => $currencyArray
        ) {
            // Validate the injected array
            (new ArrayValidator())->validate(
                array: $result,
                arrayName: 'result',
                requiredKeys: [
                    'id',
                    'transactionTime',
                    'transactionTimeLocal',
                    'transactionTimeSearch',
                    'itemId',
                    'accno',
                    'productType',
                    'vendorType',
                    'txnCode',
                    'transactionAmount',
                    'transactionCurrency',
                    'billedAmount',
                    'billedCurrency',
                    'accountBalance',
                    'exchangeRate',
                    'counterparty',
                    'paymentReference',
                    'beneficiary',
                    'country',
                    'hold'
                ]
            );

            /*💬*/ //echo $currency . PHP_EOL;
            foreach ($currencyArray as $address => $element) {
                if (is_array($element)) {
                    if (array_key_exists('balance', $element)) {
                        if ($element['balance'] > 0) {
                            /*💬*/ //echo $currencyCode . PHP_EOL;
                            /*💬*/ //echo $key . PHP_EOL;
                            /*💬*/ //echo 'Balance: ' . $element['balance'] . PHP_EOL . PHP_EOL;

                            if ($currencyCode == 'BTC') {
                                $network = 'Bitcoin';
                            } elseif (
                                $currencyCode == 'ETH' or
                                str_contains($currencyCode, 'ERC20')
                            ) {
                                $network = 'Ethereum';
                            } elseif (
                                str_contains($currencyCode, 'BEP20')
                            ) {
                                $network = 'BSC';
                            } else {
                                $network = 'XXX';
                                \Illuminate\Support\Facades\Log::warn("{$currencyCode} has no assigned network!");
                            }

                            // Determine the currency
                            $currency = \App\Models\Currency::
                            where(
                                'code',
                                $currencyCode
                            )->firstOrFail();

                            // Convert amount to base units
                            $balance = (new \App\Http\Controllers\MultiDomain\Money\MoneyConverter())
                            ->convert(
                                amount: $element['balance'],
                                currency: $currency
                            );

                            // ADAPT CURRENCY FOR SECOND BTC WALLET!

                            array_push(
                                $accountDTOs,
                                new \App\Http\Controllers\Accounts\AccountDTO(
                                    network: (string) $network,
                                    identifier: (string) 'fps'
                                        . '::' . strtolower($currencyCode)
                                        . '::' . $address,
                                    customer_id: (int) 0,
                                    networkAccountName: (string) '',
                                    label: (string) 'LCS ' . $currencyCode . ' wallet',
                                    currency_id: (int) $currency->id,
                                    balance: (int) $balance,
                                ),
                            );
                        }
                    }
                }
            }
        }

        return $accountDTOs;
    }
}
