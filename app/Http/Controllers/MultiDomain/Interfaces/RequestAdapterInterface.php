<?php

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\Http\Client\Response;

interface RequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): RequestAdapterInterface;

    /**
     * Fetch the response.
     *
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): Response;
}
