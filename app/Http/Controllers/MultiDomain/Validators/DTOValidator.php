<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class DTOValidator
{
    /**
     * Checks a DTO based on various conditions.
     *
     * @param object $dto
     * @param string $dtoName
     * @param array $requiredProperties
     * @return bool
     */
    public function validate(
        object $dto,
        string $dtoName,
        array $requiredProperties
    ): bool {
        // Validate $dtoName
        (new StringValidator())->validate(
            string: $dtoName,
            stringName: 'dtoName',
            shortestLength: 6,
            longestLength: 20,
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        $prefix = '"' . $dtoName . '" DTO ';
        if (
            count(array_intersect(array_keys(get_object_vars($dto)), $requiredProperties))
                != count($requiredProperties)
            or
            count(get_object_vars($dto)) != count($requiredProperties)
        ) {
            throw new DTOValidationException(
                message: $prefix . 'does not contain these properties: ' . implode(',', $requiredProperties)
            );
        } else {
            return true;
        }
    }
}
