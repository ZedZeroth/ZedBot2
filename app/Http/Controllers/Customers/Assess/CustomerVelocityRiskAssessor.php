<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Assess;

class CustomerVelocityRiskAssessor implements RiskAssessorInterface
{
    /**
     * Builds a velocity risk assessment DTO for
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
            (int) $customer->velocity('GBP', 7)     <= 14 /* ~2 trades per day */
            and
            (int) $customer->velocity('GBP', 30)    <= 60 /* ~2 trades per day */
            and
            (int) $customer->velocity('GBP', 90)    <= 180 /* ~2 trades per day */
            and
            (int) $customer->velocity('GBP', 365)   <= 720 /* ~2 trades per day */
        ) {
            $state = 'Standard';
        }
        if (
            (int) $customer->velocity('GBP', 7)     <= 1 + 1 /* DCA once per week */
            and
            (int) $customer->velocity('GBP', 30)    <= 4 + 1 /* DCA once per week */
            and
            (int) $customer->velocity('GBP', 90)    <= 12 + 1 /* DCA once per week */
            and
            (int) $customer->velocity('GBP', 365)   <= 52 + 1 /* DCA once per week */
        ) {
            $state = 'Lower';
        }

        if (
            $customer->riskAssessments()
                ->where('type', 'Velocity')
                ->exists()
        ) {
            if (
                $state == 'HigherUnmitigated'
                and
                $customer->riskAssessments()
                    ->where('type', 'Velocity')
                    ->first()
                    ->action
            ) {
                $state = 'HigherMitigated';
            }
        }

        // Build the DTO
        $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
            state: $state,
            identifier: 'velocity::'
                . $customer->familyName
                . '::'
                . $customer->givenName1
                . '::'
                . $customer->givenName2,
            type: 'Velocity',
            action: null,
            notes: null,
            customer_id: (int) $customer->id,
        );

        // Create/update the risk assessment
        return (new \App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater())
            ->update($riskAssessmentDTO);
    }
}
