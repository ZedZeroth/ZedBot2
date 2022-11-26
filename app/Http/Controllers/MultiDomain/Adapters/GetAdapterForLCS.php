<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Response;
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
     * @return Response
     */
    public function get(
        string $endpoint,
    ): Response {
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

        // Execute the request and return the response
        return Http::withHeaders($headers)
            ->connectTimeout(10)
            ->retry(3, 100)
            ->get($url);
    }
}
