<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface PostAdapterInterface
{
    /**
     * Makes a POST request to an API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return array
     */
    public function post(
        string $endpoint,
        array $postParameters
    ): array;
}
