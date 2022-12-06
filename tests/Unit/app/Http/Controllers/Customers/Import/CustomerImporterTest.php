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

    // Construct the account DTO
    $accountDTO = new \App\Http\Controllers\Accounts\AccountDTO(
        network: 'test',
        identifier: 'account::test::test',
        customer_id: null,
        networkAccountName: null,
        label: 'test',
        currency_id: 1,
        balance: 0,
    );

    // Construct the customer DTO
    $identifier = 'customer::test::test';
    $customerDTO = new \App\Http\Controllers\Customers\CustomerDTO(
        state: 'test',
        identifier: $identifier,
        type: 'test',
        familyName: 'Test',
        givenName1: 'Test',
        givenName2: 'Test',
        companyName: 'Test Ltd',
        preferredName: 'T',
        accountDTOs: [$accountDTO],
    );

    // Build updater and model mocks
    // MOCKING MODELS IS PROBLEMATIC
    /*
    $customerUpdaterMock = mock(\App\Http\Controllers\Customers\Update\CustomerUpdater::class)
        ->shouldReceive('update')
        ->with($customerDTO)
        ->andReturn(mock(\App\Models\Customer::class)->makePartial())
        ->getMock();
    $accountUpdaterMock = mock(\App\Http\Controllers\Accounts\Update\AccountUpdater::class)
        ->shouldReceive('update')
        ->with($accountDTO)
        ->andReturn(mock(\App\Models\Account::class)->makePartial())
        ->getMock();
    */

    // Inject into CustomerImporter's import()
    $this->assertTrue(
        (new CustomerImporter())->import(
            modelDTOs: [$customerDTO],
            customerUpdater: new \App\Http\Controllers\Customers\Update\CustomerUpdater(), //$customerUpdaterMock,
            accountUpdater: new \App\Http\Controllers\Accounts\Update\AccountUpdater()//$accountUpdaterMock
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
