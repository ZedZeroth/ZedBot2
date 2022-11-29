<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronizer;

class PaymentSynchronizer implements
    \App\Http\Controllers\MultiDomain\Interfaces\SynchronizerInterface
{
    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function sync(
        array $modelDTOs
    ): void {
        foreach ($modelDTOs as $paymentDTO) {
            //Validate DTOs
            (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
                ->validate(
                    dto: $paymentDTO,
                    dtoName: 'paymentDTO',
                    requiredProperties: [
                        'network',
                        'identifier',
                        'amount',
                        'currency_id',
                        'originator_id',
                        'beneficiary_id',
                        'memo',
                        'timestamp',
                    ]
                );

            // Create payments
            \App\Models\Payment::firstOrCreate(
                ['identifier' => $paymentDTO->identifier],
                [
                    'network' => $paymentDTO->network,
                    'amount' => $paymentDTO->amount,
                    'currency_id' => $paymentDTO->currency_id,
                    'originator_id' => $paymentDTO->originator_id,
                    'beneficiary_id' => $paymentDTO->beneficiary_id,
                    'memo' => $paymentDTO->memo,
                    'timestamp' => $paymentDTO->timestamp,
                ]
            );
        }

        return;
    }
}
