<?php

/**
 * Unit tests for the Payment class and its methods.
 */

declare(strict_types=1);

use App\Models\Account;
use App\Models\Currency;
use App\Models\Payment;

/**
 * Testing Payment relationships
 */

// POSITIVE TEST
test('GIVEN a real payment identifier
    WHEN calling originator() & beneficiary
    THEN return accounts
    ', function () {

    $payment = Payment::where(
        'identifier',
        env('ZED_TEST_PAYMENT_IDENTIFIER')
    )->firstOrFail();

    // Expect the payment to exist
    $this->assertInstanceOf(Payment::class, $payment);

    // Expect the payment to have an originator
    $this->assertInstanceOf(
        Account::class,
        $payment->originator->firstOrFail()
    );

    // Expect the payment to have a beneficiary
    $this->assertInstanceOf(
        Account::class,
        $payment->beneficiary->firstOrFail()
    );
})->group('requiresModels');

// POSITIVE TEST
test('GIVEN a real customer payment identifier
    WHEN calling currency()
    THEN return a currency
    ', function () {

    $payment = Payment::where(
        'identifier',
        env('ZED_TEST_PAYMENT_IDENTIFIER')
    )->firstOrFail();

    // Expect the payment to exist
    $this->assertInstanceOf(Payment::class, $payment);

    // Expect the payment to have a currency
    $this->assertInstanceOf(
        Currency::class,
        $payment->currency
    );
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN payment identifier "test::test::test"
WHEN calling credits() & debits()
THEN throw a ModelNotFoundException
', function () {
    $payment = Payment::where(
        'identifier',
        'test::test::test'
    )->firstOrFail();

    // Expect the account to exist
    $this->assertInstanceOf(Payment::class, $payment);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
