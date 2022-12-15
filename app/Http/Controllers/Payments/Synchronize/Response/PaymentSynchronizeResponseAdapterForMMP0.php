<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronize\Response;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;
use App\Models\Account;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\Synchronize\AccountSynchronizer;

class PaymentSynchronizeResponseAdapterForMMP0 implements
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

        $paymentDTOs = [];
        foreach ($responseArray as $addressDetails) {
            // Validate the addressDetails array
            (new ArrayValidator())->validate(
                array: $addressDetails,
                arrayName: 'addressDetails',
                requiredKeys: [
                    'address',
                    'label',
                    'numberToFetch',
                    'response'
                ],
                keysToIgnore: []
            );

            // Validate $addressDetails['address']
            (new \App\Http\Controllers\MultiDomain\Validators\BlockchainAddressValidator())->validate(
                address: $addressDetails['address'],
                addressName: 'MempoolPaymentAddress',
                network: 'Bitcoin'
            );

            // Validate $addressDetails['label']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $addressDetails['label'],
                stringName: '$addressDetails[label]',
                source: __FILE__ . ' (' . __LINE__ . ')',
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

            // Validate $addressDetails['numberToFetch']
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: $addressDetails['numberToFetch'],
                integerName: '$addressDetails[numberToFetch]',
                lowestValue: 1,
                highestValue: pow(10, 3)
            );

            // Built most recent payment DTOs for specified numberToFetch
            $numberToFetch = $addressDetails['numberToFetch'];
            foreach ($addressDetails['response'] as $index => $txDetails) {
                if ($index >= $numberToFetch) {
                    break;
                }
                // Validate the $addressDetails['response'] array
                (new ArrayValidator())->validate(
                    array: $txDetails,
                    arrayName: 'txDetails',
                    requiredKeys: [
                        'txid',
                        'version',
                        'locktime',
                        'vin',
                        'vout',
                        'size',
                        'weight',
                        'fee',
                        'status'
                    ],
                    keysToIgnore: []
                );

                // Validate $txDetails['txid']
                (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                    string: $txDetails['txid'],
                    stringName: 'txid',
                    source: __FILE__ . ' (' . __LINE__ . ')',
                    charactersToRemove: [],
                    shortestLength: 64,
                    longestLength: 64,
                    mustHaveUppercase: false,
                    canHaveUppercase: false,
                    mustHaveLowercase: false,
                    canHaveLowercase: true,
                    isAlphabetical: false,
                    isNumeric: false,
                    isAlphanumeric: true,
                    isHexadecimal: true
                );

                // Determine the currency
                $currency = \App\Models\Currency::
                        where(
                            'code',
                            'BTC'
                        )->firstOrFail();

                // Determine the associated account
                $accountIdentifier = 'bitcoin::btc::' . $addressDetails['address'];
                $account_id = Account::where('identifier', $accountIdentifier)
                    ->firstOrFail()->id;

                // Comfirmation status
                $state = \App\Models\Payments\States\Unconfirmed::class;
                if ($txDetails['status']['confirmed']) {
                    $state = \App\Models\Payments\States\Settled::class;
                }

                // Confirmation time (now if unconfirmed)
                $timestamp = date("Y-m-d H:i:s");
                if (array_key_exists('block_time', $txDetails['status'])) {
                    $timestamp = date("Y-m-d H:i:s", $txDetails['status']['block_time']);
                }

                //Shift focus to $response['vin']
                $vin = $txDetails['vin'];

                // Originators: From address to TX
                $incrementer = 0;
                foreach ($vin as $input) {
                    if (
                        $input['prevout']['scriptpubkey_address']
                        ==
                        $addressDetails['address']
                    ) {
                        // Create a payment DTO
                        array_push(
                            $paymentDTOs,
                            new \App\Http\Controllers\Payments\PaymentDTO(
                                state: $state,
                                network: (string) 'Bitcoin',
                                identifier: (string) 'bitcoin::btc'
                                . '::' . $addressDetails['address']
                                . '::' . $txDetails['txid']
                                . '::' . $incrementer,
                                amount: (int) $input['prevout']['value'],
                                currency_id: (int) $currency->id,
                                originator_id: $account_id,
                                beneficiary_id: null,
                                memo: (string) 'From ' . $addressDetails['label'],
                                timestamp: $timestamp,
                                originatorAccountDTO: null,
                                beneficiaryAccountDTO: null,
                            )
                        );
                    }
                    $incrementer++;
                }

                //Shift focus to $response['vout']
                $vout = $txDetails['vout'];

                // Beneficiary addresses
                $incrementer = 0;
                foreach ($vout as $output) {
                    if (
                        $output['scriptpubkey_address']
                        ==
                        $addressDetails['address']
                    ) {
                        // Create a payment DTO
                        array_push(
                            $paymentDTOs,
                            new \App\Http\Controllers\Payments\PaymentDTO(
                                state: $state,
                                network: (string) 'Bitcoin',
                                identifier: (string) 'bitcoin::btc'
                                . '::' . $txDetails['txid']
                                . '::' . $addressDetails['address']
                                . '::' . $incrementer,
                                amount: (int) $output['value'],
                                currency_id: (int) $currency->id,
                                originator_id: null,
                                beneficiary_id: $account_id,
                                memo: (string) 'To ' . $addressDetails['label'],
                                timestamp: $timestamp,
                                originatorAccountDTO: null,
                                beneficiaryAccountDTO: null,
                            )
                        );
                    }
                    $incrementer++;
                }
            }
        }

        return $paymentDTOs;
    }
}
