<?php

declare(strict_types=1);

namespace App\Http\Controllers\RiskAssessments\Update;

class RiskAssessmentUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update risk assessment models.
     *
     * @param ModelDtoInterface $modelDTO
     */
    public function update(
        \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface $modelDTO
    ): \Illuminate\Database\Eloquent\Model {
        //Validate DTO
        (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
            ->validate(
                dto: $modelDTO,
                dtoName: 'modelDTO',
                requiredProperties: [
                    'state',
                    'identifier',
                    'type',
                    'action',
                    'notes',
                    'customer_id',
                ]
            );

        // Validate type
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $modelDTO->type,
            stringName: '$modelDTO->type',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 3,
            longestLength: 18,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: false,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate state?
        // Validate action
        // Validate notes

        // Validate contact type
        if (
            !in_array(
                $modelDTO->type,
                config('app.ZED_RISK_ASSESSMENT_TYPES')
            )
        ) {
            throw new \Exception('Invalid risk assessment type: ' . $modelDTO->type);
        }

        // Create
        $riskAssessment = \App\Models\RiskAssessment::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'state'         => $modelDTO->state,
                'type'          => $modelDTO->type,
                'action'        => $modelDTO->action,
                'notes'         => $modelDTO->notes,
                'customer_id'   => $modelDTO->customer_id
            ]
        );

        // Update the state of existing risk assessments
        $riskAssessment->state = $modelDTO->state;
        $riskAssessment->save();

        return $riskAssessment;
    }
}
