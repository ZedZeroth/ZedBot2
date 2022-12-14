<?php

/**
 * Unit tests for the ContactUpdater class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Contacts\Update\ContactUpdater;
use App\Models\Contact;

/**
 * Testing the update() method
 */

// POSITIVE TEST
test('GIVEN a valid contactDTO
    WHEN calling update()
    THEN return a newly created contact
    ', function () {

    $contactDTO = new \App\Http\Controllers\Contacts\ContactDTO(
        state: '',
        identifier: 'email::test@test.com::ContactUpdaterTest',
        type: 'email',
        handle: 'test@test.com',
        customer_id: 1
    );

    $newContact = (new ContactUpdater())->update($contactDTO);

    // Expect a contact to have been returned
    $this->assertInstanceOf(
        Contact::class,
        $newContact
    );

    // Expect the contact to exist in the Eloquent ORM
    $this->assertTrue($newContact->exists());

    // Delete any test contacts
    Contact::where('handle', 'test@test.com')
        ->forceDelete();

    // Expect the contact to no longer exist in the database
    $this->assertNull(
        Contact::withTrashed()
            ->where('handle', 'test@test.com')
            ->first()
    );
});

// NEGATIVE TEST
test('GIVEN an invalid contact type
    WHEN calling update()
    THEN throw a StringValidationException
    ', function () {

    $contactDTO = new \App\Http\Controllers\Contacts\ContactDTO(
        state: '',
        identifier: 'email::test@test.com',
        type: 'test',
        handle: 'test@test.com',
        customer_id: null
    );

    $newContact = (new ContactUpdater())->update($contactDTO);

    // Expect a contact to have been returned
    $this->assertInstanceOf(
        Contact::class,
        $newContact
    );
})->expectExceptionMessage('Invalid contact type');
