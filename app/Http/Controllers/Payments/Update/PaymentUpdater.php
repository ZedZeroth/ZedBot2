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
                    $key != 'created_at' and
                    $key != 'updated_at'
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

        return $payment;
    }
}
