<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronizer\Requests;

use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;

class PaymentSynchronizerRequestAdapterForENM0 implements
    RequestAdapterInterface
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
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return array
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): array {
        return ($getOrPostAdapter)
            ->post(
                endpoint: env('ZED_ENM0_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
