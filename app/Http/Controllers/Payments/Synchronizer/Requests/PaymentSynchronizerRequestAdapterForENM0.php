<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronizer\Requests;

use App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

class PaymentSynchronizerRequestAdapterForENM0 implements
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
        $this->postParameters = [
            'accountCode' => env('ZED_ENM0_ACCOUNT_CODE'),
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
            platformSuffix: 'ENM0'
        );
        
        return ($getOrPostAdapter)
            ->post(
                endpoint: env('ZED_ENM0_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
