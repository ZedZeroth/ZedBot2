<?php

/**
 * Unit tests for the RiskAssessment class and its methods.
 */

declare(strict_types=1);

use App\Models\IdentityDocument;

/**
 * Testing IdentityDocument relationships
 */

// POSITIVE TEST
test('GIVEN a RiskAssessment with id=1
    WHEN calling customer()
    THEN return a customer
    ', function () {

    $riskAssessment = RiskAssessment::
        findOrFail(1);

    // Expect the risk assessment to exist
    $this->assertInstanceOf(RiskAssessment::class, $riskAssessment);

    // Expect the risk assessment to be assigned to a customer
    $this->assertInstanceOf(
        \App\Models\Customer::class,
        $riskAssessment->customer->firstOrFail()
    );
})->group('requiresModels');
