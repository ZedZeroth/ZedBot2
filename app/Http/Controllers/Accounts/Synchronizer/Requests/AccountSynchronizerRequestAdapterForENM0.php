<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Requests;

use App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

class AccountSynchronizerRequestAdapterForENM0 implements
    RequestAdapterInterface,
    AdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     */
    private array $postParameters;

    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): RequestAdapterInterface {

        // Validate the argument
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $numberToFetch,
            integerName: 'numberToFetch',
            lowestValue: 1,
            highestValue: pow(10, 5)
        );

        $this->postParameters = [
            'accountERN' => env('ZED_ENM0_ACCOUNT_ERN'),
            'take' => $numberToFetch
        ];
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
            requiredMethods: ['post'],
            platformSuffix: 'ENM0'
        );

        return ($getOrPostAdapter)
            ->post(
                endpoint: env('ZED_ENM0_BENEFICIARIES_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
