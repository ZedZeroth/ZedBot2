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
     * @param array $modelDTOs
     * @param CustomerUpdater $customerUpdater
     * @param AccountUpdater $accountUpdater
     */
    public function import(
        array $modelDTOs,
        \App\Http\Controllers\Customers\Update\CustomerUpdater $customerUpdater,
        \App\Http\Controllers\Accounts\Update\AccountUpdater $accountUpdater
    ): bool {
        foreach ($modelDTOs as $customerDTO) {
            //Validate DTOs
            (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
                ->validate(
                    dto: $customerDTO,
                    dtoName: 'customerDTO',
                    requiredProperties: [
                        'state',
                        'identifier',
                        'type',
                        'familyName',
                        'givenName1',
                        'givenName2',
                        'companyName',
                        'preferredName',
                        'accountDTOs'
                    ]
                );

            // Create customers
            $customer = $customerUpdater->update($customerDTO);

            // Create and assign accounts
            foreach ($customerDTO->accountDTOs as $accountDTO) {
                $accountDTO->customer_id = $customer->id;
                $accountUpdater->update($accountDTO);
            }
        }
        return true;
    }
}
