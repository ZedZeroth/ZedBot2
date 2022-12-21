<?php

/**
 * Unit tests for the CustomerVelocityRiskAssessor class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Assess\CustomerVelocityRiskAssessor;
use App\Models\Customer;

/**
 * Testing the assess() method
 */

// POSITIVE TEST
test('GIVEN a valid customer
    WHEN calling assess()
    THEN return a newly created velocity risk assessment
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

    $riskAssessment = (new CustomerVelocityRiskAssessor())->assess($customer);

    // Expect assessing the customer's velocity to generate a risk assessment
    $this->assertInstanceOf(
        \App\Models\RiskAssessment::class,
        $riskAssessment
    );

    // Expect the risk assessment's tag to be "VEL"
    $this->assertSame(
        'VEL',
        $riskAssessment->tag()
    );
});
