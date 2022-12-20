<?php

/**
 * Unit tests for the IdentityDocument class and its methods.
 */

declare(strict_types=1);

use App\Models\IdentityDocument;

/**
 * Testing IdentityDocument relationships
 */

// POSITIVE TEST
test('GIVEN a real identity document expiry date
    WHEN calling customer()
    THEN return a customer
    ', function () {

    $identityDocument = IdentityDocument::
        where('dateOfExpiry', config('app.ZED_SELF_ID_DOC_EXPIRY'))
        ->firstOrFail();

    // Expect the identity document to exist
    $this->assertInstanceOf(IdentityDocument::class, $identityDocument);

    // Expect the identity document to have an owner
    $this->assertInstanceOf(
        \App\Models\Customer::class,
        $identityDocument->customer->firstOrFail()
    );
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN an invalid identity document expiry date
    WHEN calling customer()
    THEN throw a ModelNotFoundException
    ', function () {

        $identityDocument = IdentityDocument::
        where('dateOfExpiry', '3000-01-01')
        ->firstOrFail();

    // Expect the identity document to exist
    $this->assertInstanceOf(IdentityDocument::class, $identityDocument);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing the emoji() method
 */

// POSITIVE TEST
test('GIVEN a real identity document expiry date
    WHEN calling emoji()
    THEN return "🚗"
    ', function () {

    $identityDocument = IdentityDocument::
        where('dateOfExpiry', config('app.ZED_SELF_ID_DOC_EXPIRY'))
        ->firstOrFail();

    // Expect the identity document to exist
    $this->assertInstanceOf(IdentityDocument::class, $identityDocument);

    // Expect the correct emoji
    $this->assertSame(
        '🚗',
        $identityDocument->emoji()
    );
})->group('requiresModels');
