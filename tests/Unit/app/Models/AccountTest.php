<?php

/**
 * Unit tests for the Account class and its methods.
 */

declare(strict_types=1);

use App\Models\Account;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Payment;

/**
 * Testing Account relationships
 */

// POSITIVE TEST
test('GIVEN a real customer account identifier
    WHEN calling debits()
    THEN return a debit
    ', function () {

    $account = Account::where(
        'identifier',
        env('ZED_TEST_ACCOUNT_IDENTIFIER')
    )->firstOrFail();

    // Expect the account to exist
    $this->assertInstanceOf(Account::class, $account);

    // Expect the account to have a debit
    $this->assertInstanceOf(
        Payment::class,
        $account->debits->firstOrFail()
    );
});

// POSITIVE TEST
test('GIVEN a real customer account identifier
    WHEN calling customer()
    THEN return the account holder
    ', function () {

    $account = Account::where(
        'identifier',
        env('ZED_TEST_ACCOUNT_IDENTIFIER')
    )->firstOrFail();

    // Expect the account to exist
    $this->assertInstanceOf(Account::class, $account);

    // Expect the account to have a holder
    $this->assertInstanceOf(
        Customer::class,
        $account->customer
    );
});

// POSITIVE TEST
test('GIVEN a real customer account identifier
    WHEN calling currency()
    THEN return a currency
    ', function () {

    $account = Account::where(
        'identifier',
        env('ZED_TEST_ACCOUNT_IDENTIFIER')
    )->firstOrFail();

    // Expect the account to exist
    $this->assertInstanceOf(Account::class, $account);

    // Expect the account to have a currency
    $this->assertInstanceOf(
        Currency::class,
        $account->currency
    );
});

// NEGATIVE TEST
test('GIVEN account identifier "test::test::test"
WHEN calling "where"
THEN throw a ModelNotFoundException
', function () {
    $account = Account::where(
        'identifier',
        'test::test::test'
    )->firstOrFail();

    // Expect Zed's account to exist
    $this->assertInstanceOf(Account::class, $account);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
