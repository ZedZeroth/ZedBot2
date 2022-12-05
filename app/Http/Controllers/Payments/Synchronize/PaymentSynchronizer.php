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
     * @param array $modelDTOs
     * @param PaymentUpdater $paymentUpdater
     * @param AccountUpdater $accountUpdater
     */
    public function sync(
        array $modelDTOs,
        \App\Http\Controllers\Payments\Update\PaymentUpdater $paymentUpdater,
        \App\Http\Controllers\Accounts\Update\AccountUpdater $accountUpdater
    ): bool {
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
                        'originatorAccountDTO',
                        'beneficiaryAccountDTO',
                    ]
                );

            // Create originator and beneficiary accounts for the payment
            $originatorAccount = $accountUpdater->update(
                $paymentDTO->originatorAccountDTO
            );
            $paymentDTO->originator_id = $originatorAccount->id;
            $beneficiaryAccount = $accountUpdater->update(
                $paymentDTO->beneficiaryAccountDTO
            );
            $paymentDTO->beneficiary_id = $beneficiaryAccount->id;

            // Create payment
            $paymentUpdater->update($paymentDTO);
        }

        return true;
    }
}
