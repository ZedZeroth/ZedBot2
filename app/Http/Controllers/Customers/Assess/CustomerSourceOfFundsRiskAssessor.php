<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Assess;

class CustomerSourceOfFundsRiskAssessor implements RiskAssessorInterface
{
    /**
     * Builds a "source of funds" risk assessment DTO for
     * a given customer, sends it to the updater
     * and returns the assessment model.
     *
     * @param Customer $customer
     * @return RiskAssessment
     */
    public function assess(
        \App\Models\Customer $customer
    ): \App\Models\RiskAssessment {

        /*💬*/ //echo $customer->fullName() . ': ';

        // Fiat risk
        if (
            $customer->riskAssessments()
                ->where('type', 'Volume')
                ->first()
        ) {
            if (
                $customer->riskAssessments()
                ->where('type', 'Volume')
                ->first()
                ->state == 'Lower'
            ) {
                $fiatRisk = match ($customer->sourceOfFiatFundsType) {
                    'verifiedSalary' => 1,
                    'salary' => 1,
                    'save' => 2,
                    'foreign' => 2,
                    'trading' => 2,
                    'gift' => 2,
                    'other' => 2,
                    default => 4
                };
            } else {
                $fiatRisk = match ($customer->sourceOfFiatFundsType) {
                    'verifiedSalary' => 1,
                    'salary' => 1,
                    'save' => 2,
                    'foreign' => 5,
                    'trading' => 5,
                    'gift' => 5,
                    'other' => 5,
                    default => 4
                };
            }
        } else {
            throw new \Exception($customer->fullName() . ' does not have a Volume RA');
        }

        /*💬*/ //echo $fiatRisk . ' ';

        // CVC risk
        $cvcRisk = match ($customer->sourceOfCvcFundsType) {
            'salary' => 1,
            'invest' => 1,
            'exchange' => 2,
            'wallet' => 2,
            'foreign' => 5,
            'trading' => 5,
            'gift' => 5,
            'other' => 5,
            default => 4
        };

        /*💬*/ //echo $cvcRisk . ' ';

        // Assign highest overall SOF risk
        if ($customer->sourceOfFiatFundsType and $customer->sourceOfCvcFundsType) {
            $max = max($fiatRisk, $cvcRisk);
        } elseif ($customer->sourceOfFiatFundsType) {
            $max = $fiatRisk;
        } elseif ($customer->sourceOfCvcFundsType) {
            $max = $cvcRisk;
        } else {
            $max = 4;
        }

        /*💬*/ //echo $max . ' ';

        $state = match ($max) {
            1 => 'Lower',
            2 => 'Standard',
            3 => 'HigherMitigated',
            4 => 'NoData',
            5 => 'HigherUnmitigated',
            default => 'NoData'
        };

        /*💬*/ //echo $state . PHP_EOL;

        if (
            $customer->riskAssessments()
                ->where('type', 'SourceOfFunds')
                ->exists()
        ) {
            if (
                $state == 'HigherUnmitigated'
                and
                $customer->riskAssessments()
                    ->where('type', 'SourceOfFunds')
                    ->first()
                    ->action
            ) {
                $state = 'HigherMitigated';
            }
        }

        // Build the DTO
        $riskAssessmentDTO = new \App\Http\Controllers\RiskAssessments\RiskAssessmentDTO(
            state: $state,
            identifier: 'sourceOfFunds::'
                . $customer->familyName
                . '::'
                . $customer->givenName1
                . '::'
                . $customer->givenName2,
            type: 'SourceOfFunds',
            action: null,
            notes: null,
            customer_id: (int) $customer->id,
        );

        // Create/update the risk assessment
        return (new \App\Http\Controllers\RiskAssessments\Update\RiskAssessmentUpdater())
            ->update($riskAssessmentDTO);
    }
}
