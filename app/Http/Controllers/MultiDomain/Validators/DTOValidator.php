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

        $prefix = '"' . $dtoName . '" DTO ';
        if (count(array_intersect(array_keys(get_object_vars($dto)), $requiredProperties)) != count($requiredProperties)) {
            throw new DTOValidationException(
                message: $prefix . 'does not contain these properties: ' . implode(',', $requiredProperties)
            );
        } else {
            return true;
        }
    }
}
