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

            if ($row['ID SURNAME']) {
                //VALIDATE EACH REQUIRED FIELD

                // Validate $row['BANK 1/SURNAME/SORT CODE/ACCOUNT NO/BANK 2/SURNAME/SORT CODE/ACCOUNT NO ETC…']
                if ($row['BANK 1/SURNAME/SORT CODE/ACCOUNT NO/BANK 2/SURNAME/SORT CODE/ACCOUNT NO ETC…']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['BANK 1/SURNAME/SORT CODE/ACCOUNT NO/BANK 2/SURNAME/SORT CODE/ACCOUNT NO ETC…'],
                        stringName: 'BANK 1/SURNAME/SORT CODE/ACCOUNT NO...',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['/', ' ', '-', '&'],
                        shortestLength: 20,
                        longestLength: pow(10, 3),
                        mustHaveUppercase: true,
                        canHaveUppercase: true,
                        mustHaveLowercase: false,
                        canHaveLowercase: true,
                        isAlphabetical: false,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['blockchain_addresses']
                if ($row['blockchain_addresses']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['blockchain_addresses'],
                        stringName: 'blockchain_addresses',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [':', '/', ' ', '’', '-'],
                        shortestLength: pow(10, 1),
                        longestLength: pow(10, 3),
                        mustHaveUppercase: true,
                        canHaveUppercase: true,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: false,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['EMAIL']
                if ($row['EMAIL']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['EMAIL'],
                        stringName: 'EMAIL',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['@', '.', '_', '-'],
                        shortestLength: pow(10, 1),
                        longestLength: pow(10, 2),
                        mustHaveUppercase: false,
                        canHaveUppercase: true,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: false,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['phone_no']
                if ($row['phone_no']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['phone_no'],
                        stringName: 'phone_no',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['+', ' '],
                        shortestLength: 10,
                        longestLength: 13,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: false,
                        canHaveLowercase: false,
                        isAlphabetical: false,
                        isNumeric: true,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['doc_type']
                if ($row['doc_type']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['doc_type'],
                        stringName: 'doc_type',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 2,
                        longestLength: 3,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['ID SURNAME']
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['ID SURNAME'],
                        stringName: 'ID SURNAME',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [' ', '\'', '-'],
                        shortestLength: 3,
                        longestLength: pow(10, 2),
                        mustHaveUppercase: false,
                        canHaveUppercase: true,
                        mustHaveLowercase: false,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );

                // Validate $row['GIVEN NAME 1']
                (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                    string: $row['GIVEN NAME 1'],
                    stringName: 'GIVEN NAME 1',
                    source: __FILE__ . ' (' . __LINE__ . ')',
                    charactersToRemove: ['-', ' '],
                    shortestLength: 2,
                    longestLength: pow(10, 2),
                    mustHaveUppercase: false,
                    canHaveUppercase: true,
                    mustHaveLowercase: false,
                    canHaveLowercase: true,
                    isAlphabetical: true,
                    isNumeric: false,
                    isAlphanumeric: true,
                    isHexadecimal: false
                );

                // Validate $row['GIVEN NAME 2']
                if ($row['GIVEN NAME 2']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['GIVEN NAME 2'],
                        stringName: 'GIVEN NAME 2',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['"', ' ', '’', '.', ':', '(', ')', ',', '£', '\'', '[', ']', '-', '/', '“', '”'],
                        shortestLength: 3,
                        longestLength: pow(10, 3),
                        mustHaveUppercase: false,
                        canHaveUppercase: true,
                        mustHaveLowercase: false,
                        canHaveLowercase: true,
                        isAlphabetical: false,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['doc_expiry']
                if ($row['doc_expiry']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['doc_expiry'],
                        stringName: 'doc_expiry',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['-'],
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
                }

                // Validate $row['customer_type']
                if ($row['customer_type']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['customer_type'],
                        stringName: 'customer_type',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 4,
                        longestLength: 10,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['dob']
                if ($row['dob']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['dob'],
                        stringName: 'dob',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: ['-'],
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
                }

                // Validate $row['loc_pob']
                if ($row['loc_pob']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['loc_pob'],
                        stringName: 'loc_pob',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 2,
                        longestLength: 2,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['loc_nat']
                if ($row['loc_nat']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['loc_nat'],
                        stringName: 'loc_nat',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 2,
                        longestLength: 2,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['loc_reside']
                if ($row['loc_reside']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['loc_reside'],
                        stringName: 'loc_reside',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 2,
                        longestLength: 2,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['bank_vol']
                if ($row['bank_vol']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['bank_vol'],
                        stringName: 'bank_vol',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 2,
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
                }

                // Validate $row['vol_action']
                if ($row['vol_mitigated']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['vol_mitigated'],
                        stringName: 'vol_mitigated',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [],
                        shortestLength: 1,
                        longestLength: 1,
                        mustHaveUppercase: false,
                        canHaveUppercase: false,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: true,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

                // Validate $row['vol_action']
                if ($row['vol_action']) {
                    (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                        string: $row['vol_action'],
                        stringName: 'vol_action',
                        source: __FILE__ . ' (' . __LINE__ . ')',
                        charactersToRemove: [' ', '.', ',', '’'],
                        shortestLength: pow(10, 1),
                        longestLength: pow(10, 3),
                        mustHaveUppercase: true,
                        canHaveUppercase: true,
                        mustHaveLowercase: true,
                        canHaveLowercase: true,
                        isAlphabetical: false,
                        isNumeric: false,
                        isAlphanumeric: true,
                        isHexadecimal: false
                    );
                }

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

                // Build the risk assessment DTOs
                $riskAssessmentDTOs = [];
                if ($row['vol_action']) {
                    if ($row['vol_mitigated'] == 'y') {
                        $action = $row['vol_action'];
                        $notes = null;
                    } else {
                        $action = null;
                        $notes = $row['vol_action'];
                    }
                    array_push(
                        $riskAssessmentDTOs,
                        new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
                            state: '',
                            identifier: 'volume'
                            . '::' . $row['ID SURNAME']
                            . '::' . $row['GIVEN NAME 1'],
                            type: 'Volume',
                            action: $action,
                            notes: $notes,
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
                        identityDocumentDTOs:   (array) $identityDocumentDTOs,
                        riskAssessmentDTOs:     (array) $riskAssessmentDTOs
                    ),
                );
            }
        }

        return $customerDTOs;
    }
}
