<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Requests;

use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidator;
use App\Http\Controllers\MultiDomain\Validators\AdapterValidator;

class AccountSynchronizerRequestAdapterForENM0 implements
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

        // Validate the argument
        (new IntegerValidator())->validate(
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
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return array
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): array {

        // Validate the argument
        (new AdapterValidator())->validate(
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
