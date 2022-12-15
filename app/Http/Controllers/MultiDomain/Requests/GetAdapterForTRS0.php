<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Requests;

class GetAdapterForTRS0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\GetAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\GetOrPostAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Makes a GET request to the TRS0 API
     *
     * @param string $endpoint
     * @return array
     */
    public function get(
        string $endpoint,
    ): array {
        // Validate $endpoint
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $endpoint,
            stringName: 'endpoint',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: ['/', '?', '=', '&'],
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

        // Build the URL
        $url = config('app.ZED_TRS0_DOMAIN')
            . config('app.ZED_TRS0_PATH')
            . $endpoint;

        // Execute the request
        $response = \Illuminate\Support\Facades\Http::connectTimeout(
            (int) config('app.ZED_CONNECT_SINGLE_TIMEOUT')
        )
            ->retry((int) config('app.ZED_CONNECT_RETRY'), 1000)
            ->timeout((int) config('app.ZED_CONNECT_ABSOLUTE_TIMEOUT'))
            ->get($url);

        // Decode the response
        $statusCode = $response->status();
        $responseArray = json_decode(
            (string) $response->getBody(),
            true
        );

        /*💬*/ //print_r($responseArray);

        //If valid then return response
        if ($statusCode == 200) {
            return $responseArray;
        // If invalid then return an empty array
        } else {
            return [];
        }
    }
}
