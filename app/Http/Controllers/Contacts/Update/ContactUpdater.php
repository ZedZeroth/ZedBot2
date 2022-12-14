<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contacts\Update;

class ContactUpdater implements
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
            shortestLength: 4,
            longestLength: 5,
            mustHaveUppercase: false,
            canHaveUppercase: false,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate handle
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->handle,
            stringName: '$modelDTO->handle',
            charactersToRemove: ['+', '@', ' ', '.', '_', '-'],
            shortestLength: pow(10, 1),
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

        // Validate contact type
        if (
            !in_array(
                $modelDTO->type,
                [
                    'phone',
                    'email'
                ]
            )
        ) {
            throw new \Exception('Invalid contact type: ' . $modelDTO->type);
        }

        // Create
        $contact = \App\Models\Contact::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'state'         => $modelDTO->state,
                'type'          => $modelDTO->type,
                'handle'        => $modelDTO->handle,
                'customer_id'   => $modelDTO->customer_id
            ]
        );

        return $contact;
    }
}
