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
*/

            // Build the DTO
            array_push(
                $customerDTOs,
                new \App\Http\Controllers\Customers\CustomerDTO(
                    // Build identifiers in importer/synchronizer!
                    // "customer"::customer_id::surname::surname_collision_increment::given_name_1::given_name_2
                    identifier: (string) 'customer'
                        . '::' . $row['ID SURNAME']
                        . '::' . $row['GIVEN NAME 1']
                        . '::' . $row['GIVEN NAME 2'],
                    type: (string) 'person', // Needs reviewing
                    familyName: (string) $row['ID SURNAME'],
                    givenName1: (string) $row['GIVEN NAME 1'],
                    givenName2: (string) $row['GIVEN NAME 2'],
                    companyName: (string) '',
                    preferredName: (string) $row['PREFERRED NAME'],
                ),
            );
        }

        return $customerDTOs;
    }
}
