<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Update;

use App\Models\Customer;

class CustomerUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update account models.
     *
     * @param ModelDtoInterface $modelDTO
     */
    public function update(
        \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface $modelDTO
    ): \Illuminate\Database\Eloquent\Model {
        //Validate DTO
        (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
            ->validate(
                dto: $modelDTO,
                dtoName: 'modelDTO',
                requiredProperties: [
                    'state',
                    'identifier',
                    'type',
                    'familyName',
                    'givenName1',
                    'givenName2',
                    'companyName',
                    'preferredName',
                    'accountDTOs',
                    'contactDTOs',
                    'identityDocumentDTOs'
                ]
            );

        // Create customer
        $customer = Customer::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'state'         => \App\Models\Customers\States\Unverified::class, // Testing
                'type'          => $modelDTO->type,
                'familyName'    => $modelDTO->familyName,
                'givenName1'    => $modelDTO->givenName1,
                'givenName2'    => $modelDTO->givenName2,
                'companyName'   => $modelDTO->companyName,
                'preferredName' => $modelDTO->preferredName,
            ]
        );

        return $customer;
    }
}
