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
     * @param array $keysToIgnore
     * @return bool
     */
    public function validate(
        array $array,
        string $arrayName,
        array $requiredKeys,
        array $keysToIgnore
    ): bool {
        // Validate $adapterName
        (new StringValidator())->validate(
            string: $arrayName,
            stringName: 'arrayName',
            charactersToRemove: [],
            shortestLength: 3,
            longestLength: pow(10, 2),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Set prefix
        $prefix = '"' . $arrayName . '" array ';

        // Remove keys to ignore
        foreach ($keysToIgnore as $key) {
            unset($array[$key]);
        }

        // Check for required keys
        if (
            count(array_intersect(array_keys($array), $requiredKeys))
                != count($requiredKeys)
            or
            count($array) != count($requiredKeys)
        ) {
            throw new ArrayValidationException(
                message:
                    $prefix
                    . 'does not contain expected keys. array_diff = '
                    . implode(
                        ',',
                        array_diff(
                            array_keys($array),
                            $requiredKeys
                        )
                    )
            );
        } else {
            return true;
        }
    }
}
