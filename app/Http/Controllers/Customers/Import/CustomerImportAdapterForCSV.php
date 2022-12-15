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
        $customerDTOs = [];
        foreach ($readerArray as $row) {
            /*💬*/ //print_r($result);

            // Validate the headers (keys) of each row
            (new ArrayValidator())->validate(
                array: $row,
                arrayName: 'row',
                requiredKeys: config('app.ZED_CUSTOMER_RECORDS_CSV_HEADERS'),
                keysToIgnore: []
            );

            //VALIDATE EACH REQUIRED FIELD
/*
            // Validate $result['sortCode']
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $result['sortCode'],
                stringName: 'sortCode',
                source: __FILE__ . ' (' . __LINE__ . ')',
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
                source: __FILE__ . ' (' . __LINE__ . ')',
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
                source: __FILE__ . ' (' . __LINE__ . ')',
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
*/
            // Multi-Currency account DTOs
            $accountDTOs = [];

            // Build the GBP account DTOs

            // Determine the currency
            $currency = \App\Models\Currency::
            where(
                'code',
                'GBP'
            )->firstOrFail();

            $bankAccountCell = $row['BANK 1/SURNAME/SORT CODE/ACCOUNT NO/BANK 2/SURNAME/SORT CODE/ACCOUNT NO ETC…'];
            if ($bankAccountCell) {
                $bankAccountArray = explode('/', $bankAccountCell);
                if (count($bankAccountArray) % 4 != 0) {
                    throw new \Exception('$accountArray "' . $bankAccountCell . '" is not divisible by 4');
                } else {
                    for ($i = 0; $i < count($bankAccountArray) / 4; $i++) {
                        $label = $bankAccountArray[4 * $i + 1];
                        $sortCode = $bankAccountArray[4 * $i + 2];
                        $accountNumber = $bankAccountArray[4 * $i + 3];
                        array_push(
                            $accountDTOs,
                            new \App\Http\Controllers\Accounts\AccountDTO(
                                network: (string) 'FPS',
                                identifier: (string) 'fps'
                                    . '::' . 'gbp'
                                    . '::' . $sortCode
                                    . '::' . $accountNumber,
                                customer_id: null,
                                networkAccountName: null,
                                label: (string) $label,
                                currency_id: (int) $currency->id,
                                balance: null,
                            ),
                        );
                    }
                }
            }

            // Build the CVC account DTOs

            $blockchainAddressCell = $row['blockchain_addresses'];
            if ($blockchainAddressCell) {
                $blockchainAddressArray = explode('//', $blockchainAddressCell);
                foreach ($blockchainAddressArray as $blockchainAddress) {
                    $blockchainAddressDetail = explode('::', $blockchainAddress);

                    // Determine the currency
                    $currency = \App\Models\Currency::
                    where(
                        'code',
                        $blockchainAddressDetail[1]
                    )->firstOrFail();

                    // Build the DTO
                    array_push(
                        $accountDTOs,
                        new \App\Http\Controllers\Accounts\AccountDTO(
                            network: (string) $blockchainAddressDetail[0],
                            identifier: (string) strtolower($blockchainAddressDetail[0])
                                . '::' . strtolower($blockchainAddressDetail[1])
                                . '::' . $blockchainAddressDetail[2],
                            customer_id: null,
                            networkAccountName: $blockchainAddressDetail[2],
                            label: (string) $blockchainAddressDetail[3],
                            currency_id: (int) $currency->id,
                            balance: null,
                        ),
                    );
                }
            }

            // Build the contact DTOs
            $contactDTOs = [];
            if ($row['EMAIL']) {
                array_push(
                    $contactDTOs,
                    new \App\Http\Controllers\Contacts\ContactDTO(
                        state: '',
                        identifier: 'email::' . $row['EMAIL'],
                        type: 'email',
                        handle: $row['EMAIL'],
                        customer_id: null
                    )
                );
            }
            if ($row['phone_no']) {
                array_push(
                    $contactDTOs,
                    new \App\Http\Controllers\Contacts\ContactDTO(
                        state: '',
                        identifier: 'phone::'
                            . str_replace(['+', ' '], '', $row['phone_no']),
                        type: 'phone',
                        handle: $row['phone_no'],
                        customer_id: null
                    )
                );
            }

            // Build the identity document DTOs
            $identityDocumentDTOs = [];
            if ($row['doc_type']) {
                // Create a dl
                array_push(
                    $identityDocumentDTOs,
                    new \App\Http\Controllers\IdentityDocuments\IdentityDocumentDTO(
                        state: '',
                        identifier: $row['doc_type']
                        . '::' . $row['ID SURNAME']
                        . '::' . $row['GIVEN NAME 1']
                        . '::' . $row['doc_expiry'],
                        type: $row['doc_type'],
                        dateOfExpiry: $row['doc_expiry'],
                        customer_id: null
                    )
                );
            }

            // Customer type
            $customerIdentifier = 'customer'
                . '::' . $row['ID SURNAME']
                . '::' . $row['GIVEN NAME 1']
                . '::' . $row['GIVEN NAME 2'];
            $type = 'individual';
            if ($row['customer_type']) {
                $type = $row['customer_type'];
            }

            // Build the customer DTO
            $dateOfBirth = null;
            if ($row['dob']) {
                $dateOfBirth = $row['dob'];
            }
            $placeOfBirth = null;
            if ($row['loc_pob']) {
                $placeOfBirth = $row['loc_pob'];
            }
            $nationality = null;
            if ($row['loc_nat']) {
                $nationality = $row['loc_nat'];
            }
            $residency = null;
            if ($row['loc_reside']) {
                $residency = $row['loc_reside'];
            }
            $volumeSnapshot = null;
            if ($row['bank_vol']) {
                $volumeSnapshot = (int) $row['bank_vol'];
            }
            array_push(
                $customerDTOs,
                new \App\Http\Controllers\Customers\CustomerDTO(
                    // Build identifiers in importer/synchronizer!
                    // "customer"::customer_id::surname::surname_collision_increment::given_name_1::given_name_2
                    state:                  \App\Models\Customers\States\Unverified::class,
                    identifier:             (string) $customerIdentifier,
                    type:                   (string) $type, // Needs reviewing
                    familyName:             (string) $row['ID SURNAME'],
                    givenName1:             (string) $row['GIVEN NAME 1'],
                    givenName2:             $row['GIVEN NAME 2'],
                    companyName:            null,
                    preferredName:          $row['PREFERRED NAME'],
                    dateOfBirth:            $dateOfBirth,
                    placeOfBirth:           $placeOfBirth,
                    nationality:            $nationality,
                    residency:              $residency,
                    volumeSnapshot:         $volumeSnapshot,
                    accountDTOs:            (array) $accountDTOs,
                    contactDTOs:            (array) $contactDTOs,
                    identityDocumentDTOs:   (array) $identityDocumentDTOs
                ),
            );
        }

        return $customerDTOs;
    }
}
