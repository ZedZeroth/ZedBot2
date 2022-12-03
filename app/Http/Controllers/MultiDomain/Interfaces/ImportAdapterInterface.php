<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Interfaces;

interface ImportAdapterInterface
{
     /**
     * Builds an array of model DTOs
     * from the CSV readerArray.
     *
     * @param array $readerArray
     * @return array
     */
    public function buildDTOs(
        array $readerArray
    ): array;
}
