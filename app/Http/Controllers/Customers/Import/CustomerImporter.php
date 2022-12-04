<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Import;

use App\Models\Customer;

class CustomerImporter// implements \App\Http\Controllers\MultiDomain\Interfaces\ImporterInterface
{
    /**
     * Uses the DTOs to create Customers for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function import(
        array $modelDTOs
    ): bool {
        foreach ($modelDTOs as $customerDTO) {
            //Validate DTOs
            (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
                ->validate(
                    dto: $customerDTO,
                    dtoName: 'customerDTO',
                    requiredProperties: [
                        'identifier',
                        'type',
                        'familyName',
                        'givenName1',
                        'givenName2',
                        'companyName',
                        'preferredName',
                    ]
                );

            // Create customers
            Customer::firstOrCreate(
                ['identifier' => $customerDTO->identifier],
                [
                    'state'         => \App\Models\Customers\States\Unverified::class, // Testing
                    'type'          => $customerDTO->type,
                    'familyName'    => $customerDTO->familyName,
                    'givenName1'    => $customerDTO->givenName1,
                    'givenName2'    => $customerDTO->givenName2,
                    'companyName'   => $customerDTO->companyName,
                    'preferredName' => $customerDTO->preferredName,
                ]
            );
        }
        return true;
    }
}
