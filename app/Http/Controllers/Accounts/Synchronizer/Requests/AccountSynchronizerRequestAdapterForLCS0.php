<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer\Requests;

use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;

class AccountSynchronizerRequestAdapterForLCS0 implements
    RequestAdapterInterface
{
     /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): RequestAdapterInterface {
        // No post parameters for LCS0
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
        return (new $getOrPostAdapter())
            ->get(
                endpoint: env('ZED_LCS0_WALLETS_ENDPOINT')
            );
    }
}