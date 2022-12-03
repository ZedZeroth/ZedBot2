<?php

/**
 * Unit tests for the CustomerImporter class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Import\CustomerImporter;

/**
 * Testing import() method
 */

// POSITIVE TEST
test('GIVEN a valid customerDTO
    WHEN calling import()
    THEN return true
    ', function () {

    // Construct the DTO
    $identifier = 'test::test50';
    $customerDTO = new \App\Http\Controllers\Customers\CustomerDTO(
        state: 'test',
        identifier: $identifier,
        type: 'test',
        familyName: 'Test',
        givenName1: 'Test',
        givenName2: 'Test',
        companyName: 'Test Ltd',
        preferredName: 'T',
    );

    // Inject into CustomerImporter's import()
    $this->assertTrue(
        (new CustomerImporter())->import(
            modelDTOs: [$customerDTO]
        )
    );

    // Delete the test entry
    $customer = \App\Models\Customer::where('identifier', $identifier)->firstOrFail();
    $customer->delete();
});

// NEGATIVE TEST
test('GIVEN an invalid array type
WHEN calling import()
THEN throw a TypeError
', function () {
    // Inject into CustomerImporter's import()
    $this->assertTrue(
        (new CustomerImporter())->import(
            modelDTOs: [0]
        )
    );
})->expectException(\TypeError::class);
