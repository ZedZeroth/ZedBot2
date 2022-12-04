<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Synchronize;

use App\Models\Account;
use App\Models\Payment;

class PaymentSynchronizer
{
    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @param array $DTOs
     * @param AccountSynchronizer $accountSynchronizer
     */
    public function sync(
        array $modelDTOs,
        \App\Http\Controllers\Accounts\Synchronize\AccountSynchronizer $accountSynchronizer
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
                        'originator_identifier',
                        'beneficiary_identifier',
                        'memo',
                        'timestamp',
                        'originatorAccountDTO',
                        'beneficiaryAccountDTO',
                    ]
                );

            // Create originator and beneficiary accounts for the payment
            $accountSynchronizer->sync(modelDTOs: [
                $paymentDTO->originatorAccountDTO,
                $paymentDTO->beneficiaryAccountDTO
            ]);

            // Create payments
            Payment::firstOrCreate(
                ['identifier' => $paymentDTO->identifier],
                [
                    //'state'             => $paymentDTO->state, // Testing
                    'network'           => $paymentDTO->network,
                    'amount'            => $paymentDTO->amount,
                    'currency_id'       => $paymentDTO->currency_id,
                    'originator_id'     => Account::
                        where('identifier', $paymentDTO->originator_identifier)
                        ->first()->id,
                    'beneficiary_id'    => Account::
                        where('identifier', $paymentDTO->beneficiary_identifier)
                        ->first()->id,
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
