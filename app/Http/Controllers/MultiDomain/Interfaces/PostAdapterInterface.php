<?php

namespace App\Http\Controllers\MultiDomain\Interfaces;

use Illuminate\Http\Client\Response;

interface PostAdapterInterface
{
    /**
     * Makes a POST request to an API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Response
     */
    public function post(
        string $endpoint,
        array $postParameters
    ): Response;
}
