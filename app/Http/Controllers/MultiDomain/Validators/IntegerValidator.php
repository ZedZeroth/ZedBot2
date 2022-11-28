<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class IntegerValidator
{
    /**
     * Checks a integer based on various conditions.
     *
     * @param int $integer
     * @param string $integerName
     * @param int $lowestValue
     * @param int $highestValue
     * @return bool
     */
    public function validate(
        int $integer,
        string $integerName,
        int $lowestValue,
        int $highestValue
    ): bool {
        /* Validating $integerName string results in code loop */

        $prefix = '"' . $integerName . '" integer ';
        if ($integer < $lowestValue) {
            throw new IntegerValidationException(
                message: $prefix . 'is less than ' . $lowestValue
            );
        } elseif ($integer > $highestValue) {
            throw new IntegerValidationException(
                message: $prefix . 'is greater than ' . $highestValue
            );
        } else {
            return true;
        }
    }
}
