<?php

/**
 * Unit tests for the CustomerImporter class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Import\CustomerImporter;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\IdentityDocument;
use App\Models\RiskAssessment;

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

    // Construct the identity document DTO
    $identityDocumentIdentifier = 'pp::test::test::CustomerImporterTest';
    $identityDocumentDTO = new \App\Http\Controllers\IdentityDocuments\IdentityDocumentDTO(
        state: '',
        identifier: $identityDocumentIdentifier,
        type: 'pp',
        dateOfExpiry: '2030-01-01',
        customer_id: null
    );

    // Construct the riskAssessment DTO
    $riskAssessmentIdentifier = 'volume::test::CustomerImporterTest';
    $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
        state: '',
        identifier: $riskAssessmentIdentifier,
        type: 'Volume',
        action: null,
        notes: null,
        customer_id: null
    );

    // Construct the customer DTO
    $customerIdentifier = 'customer::test::CustomerImporterTest';
    $customerDTO = new \App\Http\Controllers\Customers\CustomerDTO(
        state:                          'test',
        identifier:                     $customerIdentifier,
        type:                           'test',
        familyName:                     'Test',
        givenName1:                     'Test',
        givenName2:                     'Test',
        companyName:                    'Test Ltd',
        preferredName:                  'T',
        dateOfBirth:                    '2000-01-01',
        placeOfBirth:                   'gb',
        nationality:                    'gb',
        residency:                      'gb',
        volumeSnapshot:                 1000,
        sourceOfFiatFundsType:          'test',
        sourceOfFiatFundsQuote:         'test',
        sourceOfCvcFundsType:           'test',
        sourceOfCvcFundsQuote:          'test',
        destinationOfFiatFundsType:     'test',
        destinationOfFiatFundsQuote:    'test',
        destinationOfCvcFundsType:      'test',
        destinationOfCvcFundsQuote:     'test',
        accountDTOs:                    [$accountDTO],
        contactDTOs:                    [$contactDTO],
        identityDocumentDTOs:           [$identityDocumentDTO],
        riskAssessmentDTOs:             [$riskAssessmentDTO],
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
            contactUpdater: new \App\Http\Controllers\Contacts\Update\ContactUpdater(),
            identityDocumentUpdater: new \App\Http\Controllers\IdentityDocuments\Update\IdentityDocumentUpdater(),
            riskAssessmentUpdater: new \App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater()
        )
    );

    // Delete any test entries
    Account::where('identifier', $accountIdentifier)->forceDelete();
    Contact::where('identifier', $contactIdentifier)->forceDelete();
    Customer::where('identifier', $customerIdentifier)->forceDelete();
    IdentityDocument::where('identifier', $identityDocumentIdentifier)->forceDelete();
    RiskAssessment::where('identifier', $riskAssessmentIdentifier)->forceDelete();

    // Expect the models to no longer exist in the database
    $this->assertNull(
        Account::withTrashed()
            ->where('identifier', $accountIdentifier)
            ->first()
    );
    $this->assertNull(
        Contact::withTrashed()
            ->where('identifier', $contactIdentifier)
            ->first()
    );
    $this->assertNull(
        Customer::withTrashed()
            ->where('identifier', $customerIdentifier)
            ->first()
    );
    $this->assertNull(
        IdentityDocument::withTrashed()
            ->where('identifier', $identityDocumentIdentifier)
            ->first()
    );
    $this->assertNull(
        RiskAssessment::withTrashed()
            ->where('identifier', $riskAssessmentIdentifier)
            ->first()
    );
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
