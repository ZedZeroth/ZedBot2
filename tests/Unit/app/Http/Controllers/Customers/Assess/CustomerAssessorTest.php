<?php

/**
 * Unit tests for the CustomerAssessor class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Assess\CustomerAssessor;

/**
 * Testing the assess() method
 */

// POSITIVE TEST
test('GIVEN a customer
    WHEN calling assess()
    THEN return true
    ', function () {

    $customers = collect([
        \App\Models\Customer::where(
            'identifier',
            env('ZED_TEST_CUSTOMER_IDENTIFIER')
        )->firstOrFail()
    ]);

    $this->assertTrue(
        (new CustomerAssessor())->assess($customers)
    );
})->group('requiresModels');
