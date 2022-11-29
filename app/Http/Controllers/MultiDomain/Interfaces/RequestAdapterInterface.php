<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface RequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildRequestParameters(
        int $numberToFetch
    ): RequestAdapterInterface;

    /**
     * Fetch the response.
     *
     * @param GetOrPostAdapterInterface $getOrPostAdapter
     * @return array
     */
    public function fetchResponse(
        GetOrPostAdapterInterface $getOrPostAdapter
    ): array;
}
