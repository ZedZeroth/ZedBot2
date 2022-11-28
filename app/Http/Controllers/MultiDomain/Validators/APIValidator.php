<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class APIValidator
{
    /**
     * Checks an API code is valid.
     *
     * @param string $apiCode
     * @return bool
     */
    public function validate(
        string $apiCode
    ): bool {
        $prefix = '"' . $apiCode . '" ';

        // Validate the API code
        (new StringValidator())->validate(
            string: $apiCode,
            stringName: 'API',
            shortestLength: 4,
            longestLength: 4,
            containsUppercase: true,
            containsLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate the API code exists in the list
        if (!in_array($apiCode, explode(',', env('ZED_API_LIST')))) {
            throw new APIValidationException(
                message: $prefix . 'is not in the API list'
            );
        } else {
            return true;
        }
    }
}
