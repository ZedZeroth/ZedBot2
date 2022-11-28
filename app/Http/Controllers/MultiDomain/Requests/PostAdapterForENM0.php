<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Requests;

class PostAdapterForENM0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\PostAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\GetOrPostAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Makes a POST request to the ENM0 API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return array
     */
    public function post(
        string $endpoint,
        array $postParameters
    ): array {
        // Validate $endpoint
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: str_replace(['/'], '', $endpoint),
            stringName: 'endpoint',
            shortestLength: pow(10, 1),
            longestLength: pow(10, 2),
            mustHaveUppercase: false,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: false,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Build the URL
        $url = env('ZED_ENM0_DOMAIN')
            . env('ZED_ENM0_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Bearer '
                . \Illuminate\Support\Facades\DB::table('keys')
                    ->where('service', 'ENM0')
                    ->first()->key
        ];

        // Execute the request
        $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->connectTimeout((int) env('ZED_CONNECT_SINGLE_TIMEOUT'))
            ->retry((int) env('ZED_CONNECT_RETRY'), 1000)
            ->connectTimeout((int) env('ZED_CONNECT_ABSOLUTE_TIMEOUT'))
            ->post($url, $postParameters);

        // Decode the response
        $statusCode = $response->status();
        $responseBody = json_decode(
            (string) $response->getBody(),
            true
        );

        /*💬*/ //print_r($responseBody);

        //If valid then return response
        if ($statusCode == 200) {
            return $responseBody;
        // If invalid then return an empty array
        } else {
            return [];
        }
    }
}
