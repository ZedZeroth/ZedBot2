<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

class Requester
{
    /**
     * Makes a request (via specific request adapters),
     * adapts the response (via specific response adapters),
     * then returns an array of DTOs.
     *
     * @param AdapterDTO $adapterDTO
     * @param int $numberToFetch
     * @return array
     */
    public function request(
        AdapterDTO $adapterDTO,
        int $numberToFetch,
    ): array {

        //Fetch the response
        $response =
            $adapterDTO->requestAdapter
                ->buildPostParameters(
                    numberToFetch: $numberToFetch
                )
                ->fetchResponse(
                    getOrPostAdapter: $adapterDTO->getOrPostAdapter
                );

        /*💬*/ //var_dump($response);

        // Decode the response
        $statusCode = $response->status();
        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        /*💬*/ //print_r($responseBody);

        //Adapt a valid response and return the DTOs
        if ($statusCode == 200) {
            return $adapterDTO->responseAdapter
                ->buildDTOs(
                    responseBody: $responseBody
                );

        // Return an empty array for an invalid response
        } else {
            return [];
        }
    }
}
