<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\PostAdapterInterface;

class PostAdapterForENM implements
    GeneralAdapterInterface,
    PostAdapterInterface
{
    /**
     * Makes a POST request to the ENM API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return array
     */
    public function post(
        string $endpoint,
        array $postParameters
    ): array {
        // Build the URL
        $url = env('ZED_ENM_DOMAIN')
            . env('ZED_ENM_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Bearer '
                . DB::table('keys')
                    ->where('service', 'ENM')
                    ->first()->key
        ];

        // Execute the request
        $response = Http::withHeaders($headers)
            ->connectTimeout(10)
            ->retry(3, 100)
            ->post($url, $postParameters);

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
