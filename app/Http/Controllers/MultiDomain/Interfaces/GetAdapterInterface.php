<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface GetAdapterInterface
{
    /**
     * Makes a GET request to an API
     *
     * @param string $endpoint
     * @return array
     */
    public function get(
        string $endpoint,
    ): array;
}
