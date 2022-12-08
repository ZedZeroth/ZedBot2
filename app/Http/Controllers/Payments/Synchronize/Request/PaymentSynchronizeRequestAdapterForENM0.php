<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronize\Request;

use App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

class PaymentSynchronizeRequestAdapterForENM0 implements
    RequestAdapterInterface,
    AdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $requestParameters
     */
    private array $requestParameters;

    /**
     * Build the request parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildRequestParameters(
        int $numberToFetch
    ): RequestAdapterInterface {

        // Validate numberToFetch
        (new \App\Http\Controllers\MultiDomain\Validators\IntegerValidator())->validate(
            integer: $numberToFetch,
            integerName: 'numberToFetch',
            lowestValue: 1,
            highestValue: pow(10, 6)
        );

        $this->requestParameters = [
            'accountCode' => config('app.ZED_ENM0_ACCOUNT_CODE'),
            'take' => $numberToFetch,
            'goFast' => true
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
            apiSuffix: 'ENM0'
        );

        return $getOrPostAdapter
            ->post(
                endpoint: config('app.ZED_ENM0_TRANSACTIONS_ENDPOINT'),
                requestParameters: $this->requestParameters
            );
    }
}
