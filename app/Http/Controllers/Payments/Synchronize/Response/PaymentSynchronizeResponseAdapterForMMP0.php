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
        //\Illuminate\Support\Facades\Log::error($responseArray);

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

            // Validate $addressDetails['numberToFetch']
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: $addressDetails['numberToFetch'],
                integerName: '$addressDetails[numberToFetch]',
                lowestValue: 1,
                highestValue: pow(10, 3)
            );

            /*💬*/ //echo PHP_EOL . '"' . $addressDetails['label'] . '" ' . $addressDetails['address'] . PHP_EOL . PHP_EOL;

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

                $isCredit = false;
                $isDebit = false;

                //Shift focus to $response['vin']
                $vin = $txDetails['vin'];

                // Originator addresses
                foreach ($vin as $input) {
                    /*💬*/ //echo 'IN:  ' . $input['prevout']['scriptpubkey_address'] . ' ' . $input['prevout']['value'] . PHP_EOL;
                    if (
                        $input['prevout']['scriptpubkey_address']
                        ==
                        $addressDetails['address']
                    ) {
                        $isDebit = true;
                    }
                }

                //Shift focus to $response['vout']
                $vout = $txDetails['vout'];

                // Beneficiary addresses
                foreach ($vout as $output) {
                    /*💬*/ //echo 'OUT: ' . $output['scriptpubkey_address'] . ' ' . $output['value'] . PHP_EOL;
                    if (
                        $output['scriptpubkey_address']
                        ==
                        $addressDetails['address']
                    ) {
                        $isCredit = true;
                    }
                }

                // Determine the currency
                $currency = \App\Models\Currency::
                        where(
                            'code',
                            'BTC'
                        )->firstOrFail();

                if (!$isDebit and !$isCredit) {
                    throw new \Exception('Must be originator or beneficiary');
                }

                // This condition also deals with self-payments
                if ($isDebit) {
                    $originatorAccountIdentifier = 'bitcoin::btc::' . $addressDetails['address'];

                    // Build originator account DTO and sync
                    $originatorAccountDTO = new AccountDTO(
                        network: (string) 'Bitcoin',
                        identifier: (string) $originatorAccountIdentifier,
                        customer_id: null,
                        networkAccountName: (string) $addressDetails['address'],
                        label: (string) $addressDetails['label'],
                        currency_id: (int) $currency->id,
                        balance: (int) 0
                    );

                    foreach ($vout as $output) {
                        $beneficiaryAccountIdentifier = 'bitcoin::btc::' . $output['scriptpubkey_address'];
                        // Build beneficiary account DTOs and sync
                        $beneficiaryAccountDTO = new AccountDTO(
                            network: (string) 'Bitcoin',
                            identifier: (string) $beneficiaryAccountIdentifier,
                            customer_id: null,
                            networkAccountName: (string) $output['scriptpubkey_address'],
                            label: (string) 'Beneficiary of payment from ' . $addressDetails['label'],
                            currency_id: (int) $currency->id,
                            balance: (int) 0
                        );

                        // Create the payment DTOs
                        $memo = 'Payment from ' . $addressDetails['label'];
                        if ($originatorAccountIdentifier == $beneficiaryAccountIdentifier) {
                            $memo = 'Self-payment to/from ' . $addressDetails['label'];
                        }
                        array_push(
                            $paymentDTOs,
                            new \App\Http\Controllers\Payments\PaymentDTO(
                                state: \App\Models\Payments\States\Unconfirmed::class,
                                network: (string) 'Bitcoin',
                                identifier: (string) 'bitcoin::btc'
                                . '::' . $addressDetails['address']
                                . '::' . $txDetails['txid']
                                . '::' . $output['scriptpubkey_address'],
                                amount: (int) 0,
                                currency_id: (int) $currency->id,
                                originator_id: null,
                                beneficiary_id: null,
                                memo: (string) $memo,
                                timestamp: null,
                                originatorAccountDTO: $originatorAccountDTO,
                                beneficiaryAccountDTO: $beneficiaryAccountDTO,
                            )
                        );
                    }
                }

                // Self-payments are dealt with above
                if ($isCredit and !$isDebit) {
                    $beneficiaryAccountIdentifier = 'bitcoin::btc::' . $addressDetails['address'];

                    // Build the beneficiary account DTO and sync
                    $beneficiaryAccountDTO = new AccountDTO(
                        network: (string) 'Bitcoin',
                        identifier: (string) $beneficiaryAccountIdentifier,
                        customer_id: null,
                        networkAccountName: (string) $addressDetails['address'],
                        label: (string) $addressDetails['label'],
                        currency_id: (int) $currency->id,
                        balance: (int) 0
                    );

                    foreach ($vin as $input) {
                        $originatorAccountIdentifier = 'bitcoin::btc::' . $input['prevout']['scriptpubkey_address'];
                        // Build the originator account DTOs and sync
                        $originatorAccountDTO = new AccountDTO(
                            network: (string) 'Bitcoin',
                            identifier: (string) $originatorAccountIdentifier,
                            customer_id: null,
                            networkAccountName: (string) $input['prevout']['scriptpubkey_address'],
                            label: (string) 'Originator of payment to ' . $addressDetails['label'],
                            currency_id: (int) $currency->id,
                            balance: (int) 0
                        );

                        // Create the payment DTOs
                        array_push(
                            $paymentDTOs,
                            new \App\Http\Controllers\Payments\PaymentDTO(
                                state: \App\Models\Payments\States\Unconfirmed::class,
                                network: (string) 'Bitcoin',
                                identifier: (string) 'bitcoin::btc'
                                . '::' . $input['prevout']['scriptpubkey_address']
                                . '::' . $txDetails['txid']
                                . '::' . $addressDetails['address'],
                                amount: (int) 0,
                                currency_id: (int) $currency->id,
                                originator_id: null,
                                beneficiary_id: null,
                                memo: (string) 'Payment to ' . $addressDetails['label'],
                                timestamp: null,
                                originatorAccountDTO: $originatorAccountDTO,
                                beneficiaryAccountDTO: $beneficiaryAccountDTO,
                            )
                        );
                    }
                }
            }
        }

        return $paymentDTOs;
    }
}
