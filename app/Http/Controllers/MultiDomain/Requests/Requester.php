<?php

namespace App\Http\Controllers\MultiDomain\Requests;

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

        // Validate DTO property names
        (new \App\Http\Controllers\MultiDomain\Validators\DTOValidator())
            ->validate(
                dto: $adapterDTO,
                dtoName: 'adapterDTO',
                requiredProperties: [
                    'requestAdapter',
                    'responseAdapter',
                    'getOrPostAdapter'
                ]
            );

        // Validate number to fetch
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $numberToFetch,
            integerName: 'numberToFetch',
            lowestValue: 1,
            highestValue: pow(10, 6) // Maximum for payments
        );

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
