<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers\Assess;

interface RiskAssessorInterface
{
    /**
     * Builds a risk assessment DTO for
     * a given customer, sends it to the updater
     * and returns the assessment model.
     *
     * @param Customer $customer
     * @return RiskAssessment
     */
    public function assess(
        \App\Models\Customer $customer
    ): \App\Models\RiskAssessment;
}
