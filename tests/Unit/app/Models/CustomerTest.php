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
    $this->assertInstanceOf(
        Customer::class,
        $customer
    );

    // Expect the Customer to have at least one Account
    $this->assertInstanceOf(
        Account::class,
        $customer->accounts->firstOrFail()
    );
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN Customer familyName ""
    WHEN calling accounts()
    THEN throw a ModelNotFoundException
    ', function () {

    $customer = Customer::where('familyName', '')->firstOrFail();
    echo $customer->identifier;
    // Expect at least one Customer to exist
    $this->assertInstanceOf(
        Customer::class,
        $customer
    );
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing the assess() method
 */

// POSITIVE TEST
test('GIVEN "Volume"
    WHEN calling assess()
    THEN return a newly created volume risk assessment
    ', function () {

    $customer = Customer::where(
        'identifier',
        env('ZED_TEST_CUSTOMER_IDENTIFIER')
    )->firstOrFail();

    // Expect at least one Customer to exist
    $this->assertInstanceOf(
        Customer::class,
        $customer
    );

    // Expect assessing the customer's volume to generate a volume risk assessment
    $this->assertTrue(
        $customer->assess('Volume')
    );
})->group('requiresModels');

// POSITIVE TEST
test('GIVEN "Velocity"
    WHEN calling assess()
    THEN return a newly created volume risk assessment
    ', function () {

    $customer = Customer::where(
        'identifier',
        env('ZED_TEST_CUSTOMER_IDENTIFIER')
    )->firstOrFail();

    // Expect at least one Customer to exist
    $this->assertInstanceOf(
        Customer::class,
        $customer
    );

    // Expect assessing the customer's volume to generate a volume risk assessment
    $this->assertTrue(
        $customer->assess('Velocity')
    );
})->group('requiresModels');
