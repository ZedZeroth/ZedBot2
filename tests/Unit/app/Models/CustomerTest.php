<?php

/**
 * Unit tests for the Customer class and its methods.
 */

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Account;
use App\Models\RiskAssessment;

/**
 * Testing Customer relationships
 */

// POSITIVE TEST
test('GIVEN Customer identifier = env(ZED_TEST_CUSTOMER_IDENTIFIER)
    WHEN calling accounts()
    THEN an Account is returned
    ', function () {

    $customer = Customer::where(
        'identifier',
        env('ZED_TEST_CUSTOMER_IDENTIFIER')
    )->firstOrFail();

    // Expect at least one Customer to exist
    expect($customer)
        ->toBeInstanceOf(Customer::class);

    // Expect the Customer to have at least one Account
    expect(
        $customer->accounts->firstOrFail()
    )->toBeInstanceOf(Account::class);
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN Customer familyName ""
    WHEN calling accounts()
    THEN throw a ModelNotFoundException
    ', function () {

    $customer = Customer::where('familyName', '')->firstOrFail();
    echo $customer->identifier;
    // Expect at least one Customer to exist
    expect($customer)
        ->toBeInstanceOf(Customer::class);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing the assess() method
 */

// POSITIVE TEST
test('GIVEN "volume"
    WHEN calling assess()
    THEN return a newly created volume risk assessment
    ', function () {

    $customer = Customer::where(
        'identifier',
        env('ZED_TEST_CUSTOMER_IDENTIFIER')
    )->firstOrFail();

    // Expect at least one Customer to exist
    expect($customer)
        ->toBeInstanceOf(Customer::class);

    // Expect assessing the customer's volume to generate a volume risk assessment
    expect(
        $customer->assess('Volume')
    )->toBeInstanceOf(RiskAssessment::class);
})->group('requiresModels');
