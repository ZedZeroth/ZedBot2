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
    public function buildPostParameters(
        int $numberToFetch
    ): RequestAdapterInterface;

    /**
     * Fetch the response.
     *
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return array
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): array;
}
