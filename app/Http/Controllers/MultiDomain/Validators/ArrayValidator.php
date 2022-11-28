<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class ArrayValidator
{
    /**
     * Checks an array's keys.
     *
     * @param array $array
     * @param string $arrayName
     * @param array $requiredKeys
     * @return bool
     */
    public function validate(
        array $array,
        string $arrayName,
        array $requiredKeys
    ): bool {
        $prefix = '"' . $arrayName . '" array ';
        if (
            count(array_intersect(array_keys($array), $requiredKeys))
                != count($requiredKeys)
            or
            count($array) != count($requiredKeys)
        ) {
            throw new ArrayValidationException(
                message: $prefix . 'does not contain these keys: ' . implode(',', $requiredKeys)
            );
        } else {
            return true;
        }
    }
}
