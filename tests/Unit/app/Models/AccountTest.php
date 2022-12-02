<?php

/**
 * Unit tests for the Account class and its methods.
 */

declare(strict_types=1);

use App\Models\Account;
use App\Models\Payment;

/**
 * Testing Account relationships
 */

 // POSITIVE TEST
test('GIVEN Zed\'s account
    WHEN calling credits() & debits()
    THEN Payments are returned
    ', function () {

    $zedAccount = Account::where(
        'identifier',
        env('ZED_TEST_ACCOUNT_IDENTIFIER')
    )->firstOrFail();

    // Expect Zed's account to exist
    expect($zedAccount)->toBeInstanceOf(Account::class);

    // Expect Zed's account to have a credit
    expect(
        $zedAccount->credits()->firstOrFail()
    )->toBeInstanceOf(Payment::class);

    // Expect Zed's account to have a debit
    expect(
        $zedAccount->debits()->firstOrFail()
    )->toBeInstanceOf(Payment::class);
});

// NEGATIVE TEST
test('GIVEN account identifier "test::test::test"
WHEN calling credits() & debits()
THEN throw a ModelNotFoundException
', function () {
    $zedAccount = Account::where(
        'identifier',
        'test::test::test'
    )->firstOrFail();

    // Expect Zed's account to exist
    expect($zedAccount)
        ->toBeInstanceOf(Account::class);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
