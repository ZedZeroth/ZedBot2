<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\MultiDomain\Interfaces\SynchronizerInterface;
use App\Models\Payment;

class PaymentSynchronizer implements SynchronizerInterface
{
    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function sync(
        array $DTOs
    ): void {
        foreach ($DTOs as $dto) {
            Payment::firstOrCreate(
                ['identifier' => $dto->identifier],
                [
                    'network' => $dto->network,
                    'amount' => $dto->amount,
                    'currency_id' => $dto->currency_id,
                    'originator_id' => $dto->originator_id,
                    'beneficiary_id' => $dto->beneficiary_id,
                    'memo' => $dto->memo,
                    'timestamp' => $dto->timestamp,
                ]
            );
        }

        return;
    }
}
