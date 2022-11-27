<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GetAdapterInterface;

class GetAdapterForLCS implements
    GeneralAdapterInterface,
    GetAdapterInterface
{
    /**
     * Makes a GET request to the LCS API
     *
     * @param string $endpoint
     * @return array
     */
    public function get(
        string $endpoint,
    ): array {
        // Build the URL
        $url = env('ZED_LCS_DOMAIN')
            . env('ZED_LCS_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Token '
                . DB::table('keys')
                    ->where('service', 'LCS')
                    ->first()->key,
            'Content-Type' => 'application/json'
        ];

        // Execute the request
        $response = Http::withHeaders($headers)
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
