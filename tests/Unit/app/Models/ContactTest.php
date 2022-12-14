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

    $contact = Contact::
        where('type', 'phone')
        ->where('handle', env('ZED_SELF_PHONE_NUMBER'))
        ->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);

    // Expect the contact to have an owner
    $this->assertInstanceOf(
        Customer::class,
        $contact->customer->firstOrFail()
    );
})->group('requiresModels');

// POSITIVE TEST
test('GIVEN a real email address contact
    WHEN calling customer()
    THEN return a customer
    ', function () {

    $contact = Contact::
        where('type', 'email')
        ->where('handle', env('ZED_SELF_EMAIL_ADDRESS'))
        ->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);

    // Expect the contact to have an owner
    $this->assertInstanceOf(
        Customer::class,
        $contact->customer->firstOrFail()
    );
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN a false email address contact
    WHEN calling customer()
    THEN throw a ModelNotFoundException
    ', function () {

    $contact = Contact::
        where('type', 'email')
        ->where('handle', 'test@test.com')
        ->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing the emoji() method
 */

// POSITIVE TEST
test('GIVEN a real email address contact
    WHEN calling emoji()
    THEN return "📧"
    ', function () {

    $contact = Contact::
        where('type', 'email')
        ->where('handle', env('ZED_SELF_EMAIL_ADDRESS'))
        ->firstOrFail();

    // Expect the contact to exist
    $this->assertInstanceOf(Contact::class, $contact);

    // Expect the correct emoji
    $this->assertSame(
        '📧',
        $contact->emoji()
    );
})->group('requiresModels');
