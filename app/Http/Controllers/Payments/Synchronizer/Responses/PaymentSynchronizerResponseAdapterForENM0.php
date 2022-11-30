<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronizer\Responses;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;
use App\Models\Account;
use App\Http\Controllers\Accounts\AccountDTO;

class PaymentSynchronizerResponseAdapterForENM0 implements
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
            requiredKeys: ['count', 'results'],
            keysToIgnore: []
        );

        // Adapt each payment
        $paymentDTOs = [];
        foreach (
            $responseArray['results'] as $result
        ) {
            /*💬*/ //print_r($result);

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
                ],
                keysToIgnore: []
            );

            // Validate $result['transactionCurrency']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['transactionCurrency'],
                stringName: 'transactionCurrency',
                charactersToRemove: [],
                shortestLength: 3,
                longestLength: 3,
                mustHaveUppercase: true,
                canHaveUppercase: true,
                mustHaveLowercase: false,
                canHaveLowercase: false,
                isAlphabetical: true,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate $result['counterparty']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['counterparty'],
                stringName: 'counterparty',
                charactersToRemove: [',', ' ', '(', ')', '-', '.', '/', '&', '+'],
                shortestLength: 25,
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

            // Validate $result['accno']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['accno'],
                stringName: 'accno',
                charactersToRemove: [],
                shortestLength: 22,
                longestLength: 22,
                mustHaveUppercase: true,
                canHaveUppercase: true,
                mustHaveLowercase: false,
                canHaveLowercase: false,
                isAlphabetical: false,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate $result['transactionAmount']
            (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
                integer: (int) round(pow(10, 2) * $result['transactionAmount']),
                integerName: 'transactionAmount',
                lowestValue: -1 * pow(10, 7),
                highestValue: pow(10, 7)
            );

            // Validate $result['paymentReference']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['paymentReference'],
                stringName: 'paymentReference',
                charactersToRemove: ['-', '.', ' ', '/'],
                shortestLength: 0,
                longestLength: 20,
                mustHaveUppercase: false,
                canHaveUppercase: true,
                mustHaveLowercase: false,
                canHaveLowercase: true,
                isAlphabetical: false,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate $result['transactionTimeLocal']
            (new \App\Http\Controllers\MultiDomain\Validators\TimestampValidator())->validate(
                timestamp: $result['transactionTimeLocal'],
                timestampName: 'transactionTimeLocal',
                after: '2017-01-01T00:00:00.0000000+00:00',
                before: '2027-01-01T00:00:00.0000000+00:00',
            );

            // Determine the currency
            $currency = \App\Models\Currency::
                    where(
                        'code',
                        $result['transactionCurrency']
                    )->firstOrFail();

            // Determine beneficiary / originator
            $counterparty = explode(', ', $result['counterparty']);
            $beneficiary = explode(', ', $result['beneficiary']);
            if ($result['transactionAmount'] < 0) {
                $originatorNetworkAccountName = config('app.ZED_ENM0_ACCOUNT_NAME');
                $originatorLabel = config('app.ZED_ENM0_ACCOUNT_NAME');
                $originatorAccountIdentifier = $this->convertIbanToAccountIdentifier($result['accno']);

                $beneficiaryNetworkAccountName = '';
                // Enumis missing beneficiary account name
                if ($beneficiary[0]) {
                    $beneficiaryLabel = $beneficiary[0];
                } else {
                    $beneficiaryLabel = $counterparty[0];
                }
                $beneficiaryAccountIdentifier = $this->convertIbanToAccountIdentifier($beneficiary[1]);
            } else {
                $originatorNetworkAccountName = $counterparty[0];
                $originatorLabel = $counterparty[0];
                $originatorAccountIdentifier = $this->convertIbanToAccountIdentifier($counterparty[1]);

                $beneficiaryNetworkAccountName = $beneficiary[0];
                $beneficiaryLabel = $beneficiary[0];
                $beneficiaryAccountIdentifier = $this->convertIbanToAccountIdentifier($beneficiary[1]);
            }

            $accountDTOs = [];
            // Create the originator DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $originatorAccountIdentifier,
                    customer_id: (int) 0,
                    networkAccountName: (string) $originatorNetworkAccountName,
                    label: (string) $originatorLabel,
                    currency_id: (int) $currency->id,
                    balance: (int) 0
                )
            );

            // Create the beneficiary DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $beneficiaryAccountIdentifier,
                    customer_id: (int) 0,
                    networkAccountName: (string) $beneficiaryNetworkAccountName,
                    label: (string) $beneficiaryLabel,
                    currency_id: (int) $currency->id,
                    balance: (int) 0
                )
            );

            // Sync accounts
            (new \App\Http\Controllers\Accounts\Synchronizer\AccountSynchronizer())
                ->sync(modelDTOs: $accountDTOs);

            // Convert amount to base units
            $amount = (new \App\Http\Controllers\MultiDomain\Money\MoneyConverter())
            ->convert(
                amount: round(
                    abs($result['transactionAmount']),
                    $currency->decimalPlaces
                ),
                currency: $currency
            );

            // Process the payment state
            // Validate boolean string
            if ($result['hold'] != '' and $result['hold'] != 'true') {
                throw new \Exception('"hold" element is neither empty nor true');
            }

            // Assume held
            $state = \App\Models\Payments\States\Held::class;
            // If anything other than no (empty) hold element
            if ($result['hold']) {
                // Set amount to zero to prevent release of held funds
                $amount = 0;
            } else {
                // Else mark as Confirmed
                $state = \App\Models\Payments\States\Settled::class;
            }

            // Random states for testing
            /*
            $state = '\App\Models\Payments\States\\' . match (rand(0, 6)) {
                0 => 'Unconfirmed',
                1 => 'Held',
                2 => 'Settled',
                3 => 'OriginatorError',
                4 => 'AmountError',
                5 => 'Matched',
                6 => 'Reciprocated'
            };
            */

            array_push(
                $paymentDTOs,
                new \App\Http\Controllers\Payments\PaymentDTO(
                    state: (string) $state,
                    network: (string) 'FPS',
                    identifier: (string) 'enm::'
                        . $result['id'],
                    amount: (int) $amount,
                    currency_id: (int) $currency->id,
                    originator_id: (int) Account::
                        where('identifier', $originatorAccountIdentifier)
                        ->first()->id,
                    beneficiary_id: (int) Account::
                        where('identifier', $beneficiaryAccountIdentifier)
                        ->first()->id,
                    memo: (string) $result['paymentReference'],
                    timestamp: (string) date(
                        'Y-m-d H:i:s',
                        strtotime($result['transactionTimeLocal'])
                    ),
                )
            );
        }

        // Return the payment DTOs
        return $paymentDTOs;
    }

    /**
     * Convert an IBAN into this system's
     * FPS account identifier.
     *
     * @param string $iban
     * @return string
     */
    public function convertIbanToAccountIdentifier(
        string $iban
    ): string {
        return 'fps'
            . '::' . 'gbp'
            . '::' . substr($iban, -14, 6) // Sort code
            . '::' . substr($iban, -8); // Account number
    }
}
