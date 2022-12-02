<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronize;

use App\Models\Payment;

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
                        'state',
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
            Payment::firstOrCreate(
                ['identifier' => $paymentDTO->identifier],
                [
                    //'state'             => $paymentDTO->state, // Testing
                    'network'           => $paymentDTO->network,
                    'amount'            => $paymentDTO->amount,
                    'currency_id'       => $paymentDTO->currency_id,
                    'originator_id'     => $paymentDTO->originator_id,
                    'beneficiary_id'    => $paymentDTO->beneficiary_id,
                    'memo'              => $paymentDTO->memo,
                    'timestamp'         => $paymentDTO->timestamp,
                ]
            );

            // Find the model
            $payment = Payment::where('identifier', $paymentDTO->identifier)->first();
            // Cast the most recent state to the model
            $payment->state->transitionTo($paymentDTO->state);
            // Save the model and its new state to the database
            $payment->save();
        }

        return;
    }
}
