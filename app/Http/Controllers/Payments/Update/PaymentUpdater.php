<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments\Update;

use App\Models\Payment;

class PaymentUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update payment models.
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

        // Check unexpected attributes haven't changed
        $existingPayment = Payment::where('identifier', $modelDTO->identifier)->first();
        if ($existingPayment) {
            foreach ($existingPayment->toArray() as $key => $value) {
                if (
                    $key != 'id' and
                    $key != 'state' and
                    $key != 'amount' and // Held payments are stored as 0 GBP
                    $key != 'timestamp' and
                    $key != 'created_at' and
                    $key != 'updated_at' and
                    $key != 'deleted_at'
                ) {
                    if ($modelDTO->$key != $value) {
                        throw new \Exception(
                            'Model[' . $key . '] = ' . $value
                            . ' vs modelDTO[' . $key . '] = ' . $modelDTO->$key
                        );
                    }
                }
            }
        }

        // firstOrCreate because nothing should be being overridden
        // except null values (see below)
        $payment = Payment::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'network'           => $modelDTO->network,
                'amount'            => $modelDTO->amount,
                'currency_id'       => $modelDTO->currency_id,
                'originator_id'     => $modelDTO->originator_id,
                'beneficiary_id'    => $modelDTO->beneficiary_id,
                'memo'              => $modelDTO->memo,
                'timestamp'         => $modelDTO->timestamp,
            ]
        );

        // Cast the API state to the model
        $payment->state->transitionTo($modelDTO->state);

        // Only update these attributes if they were null
        if (is_null($payment->timestamp)) {
            $payment->timestamp    = $modelDTO->timestamp;
            $payment->save();
        } elseif ($payment->timestamp != $modelDTO->timestamp) {
            throw new \Exception(
                'Model[timestamp] = ' . $payment->timestamp
                . ' vs modelDTO[timestamp] = ' . $modelDTO->$timestamp
            );
        }
        // Only update these attributes if they were zero
        if ($payment->amount == 0) {
            $payment->amount    = $modelDTO->amount;
            $payment->save();
        } elseif ($payment->amount != $modelDTO->amount) {
            throw new \Exception(
                'Model[amount] = ' . $payment->amount
                . ' vs modelDTO[amount] = ' . $modelDTO->$amount
            );
        }

        return $payment;
    }
}
