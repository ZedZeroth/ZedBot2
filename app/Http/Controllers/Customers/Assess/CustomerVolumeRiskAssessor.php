<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Assess;

class CustomerVolumeRiskAssessor implements RiskAssessorInterface
{
    /**
     * Builds a volume risk assessment DTO for
     * a given customer, sends it to the updater
     * and returns the assessment model.
     *
     * @param Customer $customer
     * @return RiskAssessment
     */
    public function assess(
        \App\Models\Customer $customer
    ): \App\Models\RiskAssessment {
        // Calculate
        $state = 'HigherUnmitigated';
        if (
            (int) $customer->volume('GBP', 7)     < 10 * 1000 * 100
            and
            (int) $customer->volume('GBP', 30)    < 15 * 1000 * 100
            and
            (int) $customer->volume('GBP', 90)    < 40 * 1000 * 100
            and
            (int) $customer->volume('GBP', 365)   < 60 * 1000 * 100
        ) {
            $state = 'Standard';
        }
        if (
            (int) $customer->volume('GBP', 7)     < 1 * 1000 * 100
            and
            (int) $customer->volume('GBP', 30)    < 2 * 1000 * 100
            and
            (int) $customer->volume('GBP', 90)    < 5 * 1000 * 100
            and
            (int) $customer->volume('GBP', 365)   < 10 * 1000 * 100
        ) {
            $state = 'Lower';
        }

        // Build the DTO
        $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
            state: $state,
            identifier: 'volume::'
                . $customer->familyName
                . '::'
                . $customer->givenName1,
            type: 'Volume',
            action: null,
            notes: null,
            customer_id: (int) $customer->id,
        );

        // Create/update the risk assessment
        return (new \App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater())
            ->update($riskAssessmentDTO);
    }
}
