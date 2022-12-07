<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronize\Response;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class AccountSynchronizeResponseAdapterForTRS0 implements
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

        $accountDTOs = [];
        foreach ($responseArray as $addressDetails) {
            /*💬*/ //dd($addressDetails);

            // Validate the addressDetails array
            (new ArrayValidator())->validate(
                array: $addressDetails,
                arrayName: 'addressDetails',
                requiredKeys: [
                    'label',
                    'response'
                ],
                keysToIgnore: []
            );

            // Validate $addressDetails['label']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $addressDetails['label'],
                stringName: '$addressDetails[label]',
                charactersToRemove: [' ', '-', '’'],
                shortestLength: 3,
                longestLength: pow(10, 2),
                mustHaveUppercase: true,
                canHaveUppercase: true,
                mustHaveLowercase: true,
                canHaveLowercase: true,
                isAlphabetical: false,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate the $addressDetails['response'] array
            /*
            (new ArrayValidator())->validate(
                array: $addressDetails['response'],
                arrayName: 'response',
                requiredKeys: [
                    'address',
                    'chain_stats',
                    'mempool_stats'
                ],
                keysToIgnore: []
            );
            */

            $address = $addressDetails['response']['activePermissions'][0]['keys'][0]['address'];
            $balances = [];
            foreach ($addressDetails['response']['trc20token_balances'] as $trcTokens) {
                if ($trcTokens['tokenAbbr'] == 'USDT') {
                    $balances['USDT-TRC20'] = $trcTokens['balance'];
                }
            }
            if ($addressDetails['response']['balances'][0]['tokenAbbr'] == 'trx') {
                $balances['TRX'] = $addressDetails['response']['balances'][0]['balance'];
            }

            foreach ($balances as $token => $balance) {
                // Determine the currency
                $currency = \App\Models\Currency::
                where(
                    'code',
                    $token
                )->firstOrFail();

                // Build the DTO
                array_push(
                    $accountDTOs,
                    new \App\Http\Controllers\Accounts\AccountDTO(
                        network: (string) 'Tron',
                        identifier: (string) 'tron'
                            . '::' . strtolower($currency->code)
                            . '::' . $address,
                        customer_id: null,
                        networkAccountName: (string) $address,
                        label: (string) $addressDetails['label'],
                        currency_id: (int) $currency->id,
                        balance: (int) $balance,
                    )
                );
            }
/*

            //Shift focus to $addressDetails['response']
            $response = $addressDetails['response'];

            // Validate $responseArray['chain_stats']['funded_txo_sum']
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: $response['chain_stats']['funded_txo_sum'],
                integerName: 'funded_txo_sum',
                lowestValue: 0,
                highestValue: pow(10, 2) * pow(10, 8)
            );

            // Validate $responseArray['chain_stats']['spent_txo_sum']
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: $response['chain_stats']['spent_txo_sum'],
                integerName: 'spent_txo_sum',
                lowestValue: 0,
                highestValue: $response['chain_stats']['funded_txo_sum']
            );

            // Validate $responseArray['address']
            (new \App\Http\Controllers\MultiDomain\Validators\BlockchainAddressValidator())->validate(
                address: $response['address'],
                addressName: 'MempoolAddress',
                network: 'Bitcoin'
            );

            //Adapt the account

            // Determine the currency
            $currency = \App\Models\Currency::
            where(
                'code',
                'BTC'
            )->firstOrFail();

            // Funded minus spent (amount already in base units)
            $balance =
                $response['chain_stats']['funded_txo_sum']
                -
                $response['chain_stats']['spent_txo_sum'];

            // Build the DTO
            array_push(
                $accountDTOs,
                new \App\Http\Controllers\Accounts\AccountDTO(
                    network: (string) 'Bitcoin',
                    identifier: (string) 'bitcoin'
                        . '::' . strtolower($currency->code)
                        . '::' . $response['address'],
                    customer_id: null,
                    networkAccountName: $response['address'],
                    label: (string) $addressDetails['label'],
                    currency_id: (int) $currency->id,
                    balance: (int) $balance,
                )
            );*/
        }
        return $accountDTOs;
    }
}
