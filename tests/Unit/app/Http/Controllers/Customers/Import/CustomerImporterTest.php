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
    $accountIdentifier = 'account::test::CustomerImporterTest';
    $accountDTO = new \App\Http\Controllers\Accounts\AccountDTO(
        network: 'test',
        identifier: $accountIdentifier,
        customer_id: null,
        networkAccountName: null,
        label: 'test',
        currency_id: 1,
        balance: 0,
    );

    // Construct the contact DTO
    $contactIdentifier = 'email::test@test.com::CustomerImporterTest';
    $contactDTO = new \App\Http\Controllers\Contacts\ContactDTO(
        state: '',
        identifier: $contactIdentifier,
        type: 'email',
        handle: 'test@test.com',
        customer_id: null
    );

    // Construct the customer DTO
    $customerIdentifier = 'customer::test::CustomerImporterTest';
    $customerDTO = new \App\Http\Controllers\Customers\CustomerDTO(
        state: 'test',
        identifier: $customerIdentifier,
        type: 'test',
        familyName: 'Test',
        givenName1: 'Test',
        givenName2: 'Test',
        companyName: 'Test Ltd',
        preferredName: 'T',
        accountDTOs: [$accountDTO],
        contactDTOs: [$contactDTO],
    );

    // Build updater and model mocks
    // MOCKING MODELS IS PROBLEMATIC
    /*
    $customerUpdaterMock = mock(\App\Http\Controllers\Customers\Update\CustomerUpdater::class)
        ->shouldReceive('update')
        ->once()
        ->with($customerDTO)
        ->andReturn(mock(\App\Models\Customer::class)->makePartial())
        ->getMock();
    $accountUpdaterMock = mock(\App\Http\Controllers\Accounts\Update\AccountUpdater::class)
        ->shouldReceive('update')
        ->once()
        ->with($accountDTO)
        ->andReturn(mock(\App\Models\Account::class)->makePartial())
        ->getMock();
    */

    // Inject into CustomerImporter's import()
    $this->assertTrue(
        (new CustomerImporter())->import(
            modelDTOs: [$customerDTO],
            customerUpdater: new \App\Http\Controllers\Customers\Update\CustomerUpdater(),
            accountUpdater: new \App\Http\Controllers\Accounts\Update\AccountUpdater(),
            contactUpdater: new \App\Http\Controllers\Contacts\Update\ContactUpdater()
        )
    );

    // Delete any test entries
    \App\Models\Account::where('identifier', $accountIdentifier)->delete();
    \App\Models\Contact::where('identifier', $contactIdentifier)->delete();
    \App\Models\Customer::where('identifier', $customerIdentifier)->delete();
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
