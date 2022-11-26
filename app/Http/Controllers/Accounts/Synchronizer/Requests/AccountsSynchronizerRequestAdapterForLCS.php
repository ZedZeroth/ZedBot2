<?php

namespace App\Http\Controllers\Accounts\Synchronizer\Requests;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;

class AccountsSynchronizerRequestAdapterForLCS implements
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
        // No post parameters for LCS
        return $this;
    }

    /**
     * Fetch the response.
     *
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): Response {
        return (new $getOrPostAdapter())
            ->get(
                endpoint: env('ZED_LCS_WALLETS_ENDPOINT')
            );
    }
}
