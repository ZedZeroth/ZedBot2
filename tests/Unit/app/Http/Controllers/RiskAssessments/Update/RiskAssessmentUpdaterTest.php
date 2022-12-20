<?php

/**
 * Unit tests for the RiskAssessmentUpdater class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater;
use App\Models\RiskAssessment;

/**
 * Testing the update() method
 */

// POSITIVE TEST
test('GIVEN a valid riskAssessmentDTO
    WHEN calling update()
    THEN return a newly created risk assessment
    ', function () {

    $riskAssessmentIdentifier = 'volume::test::RiskAssessmentUpdaterTest';
    $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
        state: '', // Lower, Standard, MitigatedHigher, NoData, UnmitigatedHigher
        identifier: $riskAssessmentIdentifier,
        type: 'volume',
        action: 'test',
        notes: 'test',
        customer_id: 1
    );

    $newRiskAssessment = (new RiskAssessmentUpdater())->update($riskAssessmentDTO);

    // Expect a risk assessment to have been returned
    $this->assertInstanceOf(
        RiskAssessment::class,
        $newRiskAssessment
    );

    // Expect the risk assessment to exist in the Eloquent ORM
    $this->assertTrue($newRiskAssessment->exists());

    // Delete any test risk assessments
    RiskAssessment::where('identifier', $riskAssessmentIdentifier)
        ->forceDelete();

    // Expect the risk assessment to no longer exist in the database
    $this->assertNull(
        RiskAssessment::withTrashed()
            ->where('identifier', $riskAssessmentIdentifier)
            ->first()
    );
});

// NEGATIVE TEST
test('GIVEN an invalid risk assessment
    WHEN calling update()
    THEN throw an Exception
    ', function () {

    $riskAssessmentIdentifier = 'volume::test::RiskAssessmentUpdaterTest';
    $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
        state: '', // Lower, Standard, MitigatedHigher, NoData, UnmitigatedHigher
        identifier: $riskAssessmentIdentifier,
        type: 'test',
        action: 'test',
        notes: 'test',
        customer_id: 1
    );

    $newRiskAssessment = (new RiskAssessmentUpdater())->update($riskAssessmentDTO);

    // Expect a risk assessment to have been returned
    $this->assertInstanceOf(
        RiskAssessment::class,
        $newRiskAssessment
    );
})->expectException(\Exception::class);
