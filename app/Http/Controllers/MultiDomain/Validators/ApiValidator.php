<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Validators;

class ApiValidator
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
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 4,
            longestLength: 4,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: false,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate the API code exists in the lists
        $apiCodes = array_merge(
            config('app.ZED_EXCHANGE_API_LIST'),
            config('app.ZED_MARKET_API_LIST'),
            config('app.ZED_PAYMENT_API_LIST')
        );
        if (!in_array($apiCode, $apiCodes)) {
            throw new ApiValidationException(
                message: $prefix . 'is not in the API list'
            );
        } else {
            return true;
        }
    }
}
