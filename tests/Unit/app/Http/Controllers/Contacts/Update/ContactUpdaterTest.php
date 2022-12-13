<?php

/**
 * Unit tests for the ContactUpdater class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Contacts\Update\ContactUpdater;

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
        identifier: 'email::test@test.com',
        type: 'email',
        handle: 'test@test.com',
        customer_id: null
    );

    $newContact = (new ContactUpdater())->update($contactDTO);

    // Expect a contact to have been returned
    $this->assertInstanceOf(
        \App\Models\Contact::class,
        $newContact
    );

    // Expect the contact to exist in the Eloquent ORM
    $this->assertTrue($newContact->exists());

    // Delete the new contact
    $newContact->delete();
});
