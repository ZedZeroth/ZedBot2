<?php

/**
 * Unit tests for the Contact class and its methods.
 */

declare(strict_types=1);

use App\Models\Contact;
use App\Models\Customer;

/**
 * Testing Contact relationships
 */

// POSITIVE TEST
test('GIVEN a real phone number contact
    WHEN calling customer()
    THEN return a customer
    ', function () {

    $contact = Contact::where(
        'identifier',
        env('ZED_SELF_PHONE_NUMBER')
    )->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);

    // Expect the contact to have an owner
    $this->assertInstanceOf(
        Customer::class,
        $contact->customer->firstOrFail()
    );
});

// POSITIVE TEST
test('GIVEN a real email address contact
    WHEN calling customer()
    THEN return a customer
    ', function () {

    $contact = Contact::where(
        'identifier',
        env('ZED_SELF_EMAIL_ADDRESS')
    )->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);

    // Expect the contact to have an owner
    $this->assertInstanceOf(
        Customer::class,
        $contact->customer->firstOrFail()
    );
});
