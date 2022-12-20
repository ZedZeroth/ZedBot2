<?php

/**
 * Unit tests for the IdentityDocumentUpdater class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\IdentityDocuments\Update\IdentityDocumentUpdater;
use App\Models\IdentityDocument;

/**
 * Testing the update() method
 */

// POSITIVE TEST
test('GIVEN a valid identityDocumentDTO
    WHEN calling update()
    THEN return a newly created identity document
    ', function () {

    $identityDocumentIdentifier = 'pp::test::test::IdentityDocumentUpdater';
    $identityDocumentDTO = new \App\Http\Controllers\IdentityDocuments\IdentityDocumentDTO(
        state: '',
        identifier: $identityDocumentIdentifier,
        type: 'pp',
        dateOfExpiry: '2030-01-01',
        customer_id: 1
    );

    $newIdentityDocument = (new IdentityDocumentUpdater())->update($identityDocumentDTO);

    // Expect an identity document to have been returned
    $this->assertInstanceOf(
        IdentityDocument::class,
        $newIdentityDocument
    );

    // Expect the identity document to exist in the Eloquent ORM
    $this->assertTrue($newIdentityDocument->exists());

    // Delete any test identity documents
    IdentityDocument::where('identifier', $identityDocumentIdentifier)
        ->forceDelete();

    // Expect the identity document to no longer exist in the database
    $this->assertNull(
        IdentityDocument::withTrashed()
            ->where('identifier', $identityDocumentIdentifier)
            ->first()
    );
});

// NEGATIVE TEST
test('GIVEN an invalid date of birth
    WHEN calling update()
    THEN throw a QueryException
    ', function () {

    $identityDocumentIdentifier = 'pp::test::test::IdentityDocumentUpdaterTest';
    $identityDocumentDTO = new \App\Http\Controllers\IdentityDocuments\IdentityDocumentDTO(
        state: '',
        identifier: $identityDocumentIdentifier,
        type: 'pp',
        dateOfExpiry: '20000-01-01',
        customer_id: null
    );

    $newIdentityDocument = (new IdentityDocumentUpdater())->update($identityDocumentDTO);

    // Expect an identity document to have been returned
    $this->assertInstanceOf(
        IdentityDocument::class,
        $newIdentityDocument
    );
})->expectException(\Illuminate\Database\QueryException::class);
