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
        // Validate type
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->type,
            stringName: '$modelDTO->type',
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
                'nationality'   => $modelDTO->nationality,
                'placeOfBirth'  => $modelDTO->placeOfBirth,
                'dateOfBirth'   => $modelDTO->dateOfBirth,
                'dateOfExpiry'  => $modelDTO->dateOfExpiry,
                'customer_id'   => $modelDTO->customer_id
            ]
        );

        return $identityDocument;
    }
}
