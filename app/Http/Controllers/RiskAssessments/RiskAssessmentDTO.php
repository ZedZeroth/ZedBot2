<?php

declare(strict_types=1);

namespace App\Http\Controllers\RiskAssessments;

class RiskAssessmentDTO implements
    \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface
{
    /**
     * The risk assessment data transfer object
     * for moving risk assessment data between
     * an adapter and the updater.
     */
    public function __construct(
        public string $state,
        public string $identifier,
        public string $type,
        public ?string $action,
        public ?string $notes,
        public ?int $customer_id,
    ) {
    }
}
