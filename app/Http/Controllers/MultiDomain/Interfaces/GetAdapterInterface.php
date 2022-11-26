<?php

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\Http\Client\Response;

interface GetAdapterInterface
{
    /**
     * Makes a GET request to an API
     *
     * @param string $endpoint
     * @return Response
     */
    public function get(
        string $endpoint,
    ): Response;
}
