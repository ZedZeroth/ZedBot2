<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Update;

use App\Models\Account;

class AccountUpdater implements
    \App\Http\Controllers\MultiDomain\Interfaces\UpdaterInterface
{
    /**
     * Uses the DTOs to create/update account models.
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
                    'network',
                    'identifier',
                    'customer_id',
                    'networkAccountName',
                    'label',
                    'currency_id',
                    'balance',
                ]
            );

        // firstOrCreate because nothing should be being overridden
        // except null values or state (see below)
        $account = Account::firstOrCreate(
            ['identifier' => $modelDTO->identifier],
            [
                'network'       => $modelDTO->network,
                'currency_id'   => $modelDTO->currency_id,
                'balance'       => $modelDTO->balance,
            ]
        );

        // Cast the API state to the model
        // $account->state->transitionTo($modelDTO->state);

        // If a networkAccountName is passed then override null
        if (
            !$account->networkAccountName // No existing name
            and
            $modelDTO->networkAccountName // New name is passed
        ) {
            $account->update(
                ['networkAccountName' => $modelDTO->networkAccountName]
            );
        }

        // If an account holder (customer) is passed then override null
        if (
            !$account->customer_id // No existing account holder
            and
            $modelDTO->customer_id // New account holder is passed
        ) {
            $account->update(
                ['customer_id' => $modelDTO->customer_id]
            );
        }

        return $account;
    }
}
