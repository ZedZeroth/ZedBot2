<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronize\Response;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;
use App\Models\Account;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\Synchronize\AccountSynchronizer;

class PaymentSynchronizeResponseAdapterForTRS0 implements
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
                    'response'
                ],
                keysToIgnore: []
            );

            // Validate $addressDetails['address']
            (new \App\Http\Controllers\MultiDomain\Validators\BlockchainAddressValidator())->validate(
                address: $addressDetails['address'],
                addressName: 'TronscanPaymentAddress',
                network: 'Tron'
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

            /*💬*/ //echo PHP_EOL . '"' . $addressDetails['label'] . '" ' . $addressDetails['address'] . PHP_EOL . PHP_EOL;

            $contractInfo = $addressDetails['response']['contractInfo'];
            // Built most recent payment DTOs for specified numberToFetch
            foreach ($addressDetails['response']['data'] as $txDetails) {
                /*💬*/ //dd($txDetails);
                /*
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
                */

                $isCredit = false;
                $isDebit = false;

                $token = null;
                if (array_key_exists('trigger_info', $txDetails)) {
                    if (
                        $contractInfo[
                            $txDetails['trigger_info']['contract_address']
                        ]['tag1'] == 'USDT Token'
                    ) {
                        $token = 'USDT-TRC20';
                    }
                    $amount = $txDetails['trigger_info']['parameter']['_value'];
                    if ($txDetails['ownerAddress'] == $addressDetails['address']) {
                        $isDebit = true;
                    }
                    if ($txDetails['trigger_info']['parameter']['_to'] == $addressDetails['address']) {
                        $isCredit = true;
                    }
                } else {
                    $token = 'TRX';
                    $amount = $txDetails['contractData']['amount'];
                    if ($txDetails['ownerAddress'] == $addressDetails['address']) {
                        $isDebit = true;
                    }
                    if ($txDetails['toAddress'] == $addressDetails['address']) {
                        $isCredit = true;
                    }
                }
                if (!$isDebit and !$isCredit) {
                    // This transaction is a suspected malicious attack as it was not initiated by account
                    // and the transfer amount is 0. Assets in the account remain intact and have not been transferred out.
                    // throw new \Exception('Must be originator or beneficiary: ' . $txDetails['hash']);
                } else {
                    if ($token) {
                        // Determine the currency
                        $currency = \App\Models\Currency::
                                where(
                                    'code',
                                    $token
                                )->firstOrFail();

                        // New system
                        $originator_id = null;
                        $beneficiary_id = null;
                        $accountIdentifier = 'tron::' . strtolower($token) . '::' . $addressDetails['address'];
                        $account_id = Account::where('identifier', $accountIdentifier)
                            ->firstOrFail()->id;
                        if ($isDebit) {
                            $originator_id = $account_id;
                            $memo = 'From ' . $addressDetails['label'];
                            if ($isCredit) {
                                $beneficiary_id = $originator_id;
                                $memo = 'To/from ' . $addressDetails['label'];
                            }
                        } elseif ($isCredit) {
                            $beneficiary_id = $account_id;
                            $memo = 'To ' . $addressDetails['label'];
                        }

                        $state = \App\Models\Payments\States\Unconfirmed::class;

                        // Create the payment DTO
                        array_push(
                            $paymentDTOs,
                            new \App\Http\Controllers\Payments\PaymentDTO(
                                state: $state,
                                network: (string) 'Tron',
                                identifier: (string) 'tron::' . strtolower($token)
                                . '::' . $txDetails['hash'],
                                amount: (int) $amount,
                                currency_id: (int) $currency->id,
                                originator_id: (int) $originator_id,
                                beneficiary_id: (int) $beneficiary_id,
                                memo: (string) $memo,
                                timestamp: date("Y-m-d H:i:s", $txDetails['timestamp'] / 1000),
                                originatorAccountDTO: null,
                                beneficiaryAccountDTO: null,
                            )
                        );
                    }
                }
            }
        }

        return $paymentDTOs;
    }
}
