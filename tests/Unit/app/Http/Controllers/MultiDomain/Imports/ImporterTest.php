<?php

/**
 * Unit tests for the Importer class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\MultiDomain\Imports\Importer;

/**
 * Testing import() method
 */

 // POSITIVE TEST
test('GIVEN a valid CSV reader & adapter
WHEN calling import()
THEN return a CSV array
', function () {

    // Build parameters
    $csvReaderMock = mock(\App\Http\Controllers\MultiDomain\Imports\CsvReader::class)
        ->shouldReceive('read')
        ->with('test.csv')
        ->andReturn([
            ['header1', 'header2'],
            ['row1field1', ['row1field2']],
            ['row2field1', ['row2field2']],
        ])
        ->getMock();

    $csvAdapterMock = mock(\App\Http\Controllers\Customers\Import\CustomerImportAdapterForCSV::class)
        ->shouldReceive('buildDTOs')
        ->with($csvReaderMock)
        ->andReturn([
            new \App\Http\Controllers\Customers\CustomerDTO(
                state: 'test',
                identifier: 'test::test',
                type: 'test',
                familyName: 'Test',
                givenName1: 'Test',
                givenName2: 'Test',
                companyName: 'Test Ltd',
                preferredName: 'T',
            )
        ])
        ->getMock();

    // Inject into Importer's import()
    $this->assertTrue(
        (new Importer())->import(
            readerArray: $csvReaderMock->read('test.csv'),
            importerAdapter: $csvAdapterMock
        )
    );
});
//})->expectException(\TypeError::class);
