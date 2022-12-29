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
            (int) $customer->volume('GBP', 7)     < 10 * 1000 * 100 /* 10k GBP in a single week */
            and
            (int) $customer->volume('GBP', 30)    < 15 * 1000 * 100 /* 500 GBP/day */
            and
            (int) $customer->volume('GBP', 90)    < 40 * 1000 * 100 /* ~€500 GBP/day */
            and
            (int) $customer->volume('GBP', 365)   < 60 * 1000 * 100 /* Enumis limit (60k GBP/yr) */
        ) {
            $state = 'Standard';
        }
        if (
            (int) $customer->volume('GBP', 7)     < 1 * 1000 * 100 /* 1k GBP in a single week */
            and
            (int) $customer->volume('GBP', 30)    < 2 * 1000 * 100 /* 2k GBP per month */
            and
            (int) $customer->volume('GBP', 90)    < 5 * 1000 * 100 /* 5k GBP in 3 months */
            and
            (int) $customer->volume('GBP', 365)   < 10 * 1000 * 100 /* 10k GPB/yr */
        ) {
            $state = 'Lower';
        }

        if (
            $customer->riskAssessments()
                ->where('type', 'Volume')
                ->exists()
        ) {
            if (
                $state == 'HigherUnmitigated'
                and
                $customer->riskAssessments()
                    ->where('type', 'Volume')
                    ->first()
                    ->action
            ) {
                $state = 'HigherMitigated';
            }
        }

        // Build the DTO
        $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
            state: $state,
            identifier: 'volume::'
                . $customer->familyName
                . '::'
                . $customer->givenName1
                . '::'
                . $customer->givenName2,
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
