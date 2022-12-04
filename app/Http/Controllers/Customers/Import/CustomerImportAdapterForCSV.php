<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Import;

use App\Http\Controllers\MultiDomain\Validators\ArrayValidator;

class CustomerImportAdapterForCSV implements
    \App\Http\Controllers\MultiDomain\Interfaces\ImportAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Builds an array of model DTOs
     * from the readerArray.
     *
     * @param array $readerArray
     * @return array
     */
    public function buildDTOs(
        array $readerArray
    ): array {
        /*💬*/ //print_r($responseArray);

        // Adapt each account
        $accountDTOs = [];
        foreach ($readerArray as $row) {
            /*💬*/ //print_r($result);

            // Validate the injected array
            (new ArrayValidator())->validate(
                array: $row,
                arrayName: 'row',
                requiredKeys: config('app.ZED_CUSTOMER_RECORDS_CSV_HEADERS'),
                keysToIgnore: []
            );

            // Skip non-UK accounts
            if (array_key_exists('swift', $result)) {
                continue;
            }

            // Proceed with array validation
            (new ArrayValidator())->validate(
                array: $result,
                arrayName: 'result',
                requiredKeys: [
                    'ern',
                    'nickName',
                    'beneficiaryERN',
                    'confidenceScore',
                    'riskWeight',
                    'sortCode',
                    'accountNumber',
                    'accountName',
                    'createdDate',
                    'owners',
                    'hasOwners'
                ],
                keysToIgnore: ['modifiedDate', 'bankName']
            );

            // Validate $result['sortCode']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['sortCode'],
                stringName: 'sortCode',
                charactersToRemove: [],
                shortestLength: 6,
                longestLength: 6,
                mustHaveUppercase: false,
                canHaveUppercase: false,
                mustHaveLowercase: false,
                canHaveLowercase: false,
                isAlphabetical: false,
                isNumeric: true,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate $result['accountNumber']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['accountNumber'],
                stringName: 'accountNumber',
                charactersToRemove: [],
                shortestLength: 8,
                longestLength: 8,
                mustHaveUppercase: false,
                canHaveUppercase: false,
                mustHaveLowercase: false,
                canHaveLowercase: false,
                isAlphabetical: false,
                isNumeric: true,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Validate $result['accountName']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['accountName'],
                stringName: 'accountName',
                charactersToRemove: [' ', '/', '-', '&', '(', ')', '\'', '.'],
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

            // Determine the currency
            $currency = \App\Models\Currency::
                    where(
                        'code',
                        'GBP'
                    )->firstOrFail();

            // Build the DTO
            array_push(
                $accountDTOs,
                new \App\Http\Controllers\Accounts\AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'fps'
                        . '::' . 'gbp'
                        . '::' . $result['sortCode']
                        . '::' . $result['accountNumber'],
                    customer_id: (int) null,
                    networkAccountName: (string) '',
                    label: (string) $result['accountName'],
                    currency_id: (int) $currency->id,
                    balance: (int) 0,
                ),
            );
        }

        return $accountDTOs;
    }
}
