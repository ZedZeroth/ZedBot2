<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface ResponseAdapterInterface
{
     /**
     * Builds an array of model DTOs
     * from the responseArray.
     *
     * @param array $responseArray
     * @return array
     */
    public function buildDTOs(
        array $responseArray
    ): array;
}
