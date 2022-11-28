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
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate the API code exists in the lists
        $apiCodes =
            env('ZED_EXCHANGE_API_LIST') . ',' .
            env('ZED_MARKET_API_LIST') . ',' .
            env('ZED_NETWORK_API_LIST');
        if (!in_array($apiCode, explode(',', $apiCodes))) {
            throw new APIValidationException(
                message: $prefix . 'is not in the API list'
            );
        } else {
            return true;
        }
    }
}
