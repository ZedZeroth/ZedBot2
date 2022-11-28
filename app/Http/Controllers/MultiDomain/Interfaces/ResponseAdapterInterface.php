<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface ResponseAdapterInterface
{
     /**
     * Builds an array of model DTOs
     * from the responseBody.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array;
}
