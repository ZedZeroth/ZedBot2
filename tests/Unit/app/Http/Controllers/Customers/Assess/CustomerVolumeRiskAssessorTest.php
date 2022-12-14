<?php

/**
 * Unit tests for the CustomerVolumeRiskAssessor class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\Assess\CustomerVolumeRiskAssessor;
use App\Models\Customer;

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
    $this->assertInstanceOf(
        Customer::class,
        $customer
    );

    $riskAssessment = (new CustomerVolumeRiskAssessor())->assess($customer);

    // Expect assessing the customer's volume to generate a risk assessment
    $this->assertInstanceOf(
        \App\Models\RiskAssessment::class,
        $riskAssessment
    );

    // Expect the risk assessment's tag to be "VOL"
    $this->assertSame(
        'VOL',
        $riskAssessment->tag()
    );
});
