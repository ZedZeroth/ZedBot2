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

        $rowNumber = 0;
        $columnsToIgnore = [
            'DAYS TRADED',
            'TOTAL TRADED',
            'LAST TRADE DATE',
            'LAST DAY AMOUNT',
            'OVER_UNDER_CUMULATIVE',
            'GIVEN NAME 2',
            '1 BIO PAGE',
            '2 BANK STATEMENT',
            '3 VIDEO DECLARATION',
            'CODE'
        ];
        foreach ($secondaryReaderArray as $secondaryRow) {
            foreach ($secondaryRow as $header => $cell) {
                if (!in_array($header, $columnsToIgnore)) {
                    if (array_key_exists($header, $primaryReaderArray[$rowNumber])) {
                        if (
                            $primaryReaderArray[$rowNumber][$header] != $cell
                            and
                            $cell != ''
                        ) {
                            echo PHP_EOL;
                            echo ($rowNumber + 2) . '::' . $header;
                            echo PHP_EOL;
                            echo '1: ' . $primaryReaderArray[$rowNumber][$header];
                            echo PHP_EOL;
                            echo '2: ' . $cell;
                            echo PHP_EOL;
                        }
                    }
                }
            }
            $rowNumber++;
        }
        $comparisonArray = [];
        return $comparisonArray;
    }
}
