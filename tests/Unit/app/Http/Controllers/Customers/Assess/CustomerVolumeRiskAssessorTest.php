<?php

/**
 * Unit tests for the CustomerVolumeRiskAssessor class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Assess\CustomerVolumeRiskAssessor;

/**
 * Testing the assess() method
 */

// POSITIVE TEST
test('GIVEN a valid customer
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
        (new CustomerVolumeRiskAssessor())->assess($customer)
    )->toBeInstanceOf(RiskAssessment::class);
});
