<?php

namespace App\Http\Controllers\MultiDomain\Imports;

class CsvReader
{
    /**
     * Converts a CSV file into an associative
     * array with the column headers as keys.
     *
     * @param string $fileName
     * @return array
     */
    public function read(
        string $fileName
    ): array {

        // VALIDATE FILENAME

        $csvFilePath = \Illuminate\Support\Facades\Storage::path('csv/' . $fileName);

        $headers = [];
        $readerArray = [];
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                if (!$headers) {
                    $headers = $row;
                } else {
                    $counter = 0;
                    $rowArray = [];
                    foreach ($row as $cell) {
                        $rowArray[$headers[$counter]] = $cell;
                        $counter++;
                    }
                    array_push($readerArray, $rowArray);
                }
            }
            fclose($handle);
        }
        return $readerArray;
    }
}
