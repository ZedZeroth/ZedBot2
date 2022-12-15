<?php

declare(strict_types=1);

namespace App\Http\Controllers\IdentityDocuments\Update;

class IdentityDocumentUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update payment models.
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
                    'dateOfExpiry',
                    'customer_id',
                ]
            );

        // Validate type
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->type,
            stringName: '$modelDTO->type',
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

        // Validate countries
        // Country ISO validator

        // Validate dates
        // Date validator

        // Validate contact type
        if (
            !in_array(
                $modelDTO->type,
                [
                    'brp',
                    'dl',
                    'pp'
                ]
            )
        ) {
            throw new \Exception('Invalid identity document type: ' . $modelDTO->type);
        }

        // Create
        $identityDocument = \App\Models\IdentityDocument::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'state'         => $modelDTO->state,
                'type'          => $modelDTO->type,
                'dateOfExpiry'  => $modelDTO->dateOfExpiry,
                'customer_id'   => $modelDTO->customer_id
            ]
        );

        return $identityDocument;
    }
}
