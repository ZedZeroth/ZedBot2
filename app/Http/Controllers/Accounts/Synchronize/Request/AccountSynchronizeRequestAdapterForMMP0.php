<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronize\Request;

use App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

class AccountSynchronizeRequestAdapterForMMP0 implements
    RequestAdapterInterface,
    AdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildRequestParameters(
        int $numberToFetch
    ): RequestAdapterInterface {
        // No request parameters
        return $this;
    }

    /**
     * Fetch the response.
     *
     * @param GetOrPostAdapterInterface $getOrPostAdapter
     * @return array
     */
    public function fetchResponse(
        \App\Http\Controllers\MultiDomain\Interfaces\GetOrPostAdapterInterface $getOrPostAdapter
    ): array {

        // Validate the injected adapter
        (new \App\Http\Controllers\MultiDomain\Validators\AdapterValidator())->validate(
            adapter: $getOrPostAdapter,
            adapterName: 'getOrPostAdapter',
            requiredMethods: ['get'],
            apiSuffix: 'MMP0'
        );

        /**
         * Build an array of responses,
         * one for each address in the database.
         */
        $responseArray = [];

        $addressDetailsCollection = \Illuminate\Support\Facades\DB::table('blockchain_addresses')
                    ->where('network', 'Bitcoin')
                    ->get();

        foreach ($addressDetailsCollection->all() as $addressDetails) {
            // Validate $addressDetails->address & $addressDetails->network
            (new \App\Http\Controllers\MultiDomain\Validators\BlockchainAddressValidator())->validate(
                address: $addressDetails->address,
                addressName: 'MempoolAddress',
                network: $addressDetails->network
            );

            // Validate $addressDetails->label
            (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
                string: $addressDetails->label,
                stringName: '$addressDetails->label',
                charactersToRemove: [' ', '-'],
                shortestLength: 3,
                longestLength: pow(10, 2),
                mustHaveUppercase: true,
                canHaveUppercase: true,
                mustHaveLowercase: true,
                canHaveLowercase: true,
                isAlphabetical: false,
                isNumeric: false,
                isAlphanumeric: true,
                isHexadecimal: false
            );

            // Push details to the array
            array_push(
                $responseArray,
                [
                    'label' => $addressDetails->label,
                    'response' => (new $getOrPostAdapter())
                        ->get(
                            endpoint: config('app.ZED_MMP0_ADDRESS_ENDPOINT')
                            . $addressDetails->address
                        )
                ]
            );
        }

        return $responseArray;
    }
}
