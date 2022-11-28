<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Requests;

class GetAdapterForLCS0 implements
    \App\Http\Controllers\MultiDomain\Interfaces\GetAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\GetOrPostAdapterInterface,
    \App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface
{
    /**
     * Makes a GET request to the LCS0 API
     *
     * @param string $endpoint
     * @return array
     */
    public function get(
        string $endpoint,
    ): array {
        // Build the URL
        $url = env('ZED_LCS0_DOMAIN')
            . env('ZED_LCS0_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Token '
                . \Illuminate\Support\Facades\DB::table('keys')
                    ->where('service', 'LCS0')
                    ->first()->key,
            'Content-Type' => 'application/json'
        ];

        // Execute the request
        $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->connectTimeout(10)
            ->retry(3, 100)
            ->get($url);

        // Decode the response
        $statusCode = $response->status();
        $responseBody = json_decode(
            $response->getBody(),
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
