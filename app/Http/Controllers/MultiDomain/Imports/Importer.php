<?php

namespace App\Http\Controllers\MultiDomain\Imports;

class Importer
{
    /**
     * Adapts the CSV array (via specific import adapters),
     * then returns an array of DTOs.
     *
     * @param array $csvArray
     * @param ImportAdapterInterface $importerAdapter
     * @return array
     */
    public function import(
        array $readerArray,
        \App\Http\Controllers\MultiDomain\Interfaces\ImportAdapterInterface $importerAdapter,
    ): array {

        // VALIDATION

        // CsvReader is injected with CSV filename upon injection,
        // hence array is injected...

        //Adapt the CSV array and return the DTOs
        if ($readerArray) {
            return $importerAdapter
                ->buildDTOs(
                    readerArray: $readerArray
                );
        }
        return [];
    }
}
