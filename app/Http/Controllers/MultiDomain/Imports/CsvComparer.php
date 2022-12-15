<?php

namespace App\Http\Controllers\MultiDomain\Imports;

class CsvComparer
{
    /**
     * Determines information that exists in
     * a secondary CSV that does not exist in
     * the primary CSV.
     *
     * @param string $primaryFileName
     * @param string $secondaryFileName
     * @return array
     */
    public function compare(
        string $primaryFileName,
        string $secondaryFileName
    ): array {

        // VALIDATE FILENAME

        $primaryReaderArray = (new CsvReader())->read($primaryFileName);
        $secondaryReaderArray = (new CsvReader())->read($secondaryFileName);
        $comparisonArray = [];
        return $comparisonArray;
    }
}
