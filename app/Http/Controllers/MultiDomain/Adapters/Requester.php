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
        $responseBody =
            $adapterDTO->requestAdapter
                ->buildPostParameters(
                    numberToFetch: $numberToFetch
                )
                ->fetchResponse(
                    getOrPostAdapter: $adapterDTO->getOrPostAdapter
                );

        //Adapt a valid response and return the DTOs
        if ($responseBody) {
            return $adapterDTO->responseAdapter
                ->buildDTOs(
                    responseBody: $responseBody
                );
        }
    }
}
