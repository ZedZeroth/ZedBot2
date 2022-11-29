<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Responses;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class AccountSynchronizerResponseAdapterForMMP0 implements
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
                charactersToRemove: [' ', '-'],
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
                    customer_id: (int) 0,
                    networkAccountName: $response['address'],
                    label: (string) $addressDetails['label'],
                    currency_id: (int) $currency->id,
                    balance: (int) $balance,
                )
            );
        }

        return $accountDTOs;
    }
}
