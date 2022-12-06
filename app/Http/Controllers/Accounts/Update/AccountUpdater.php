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

        // Update these attributes if model already exists
        $account = Account::firstOrNew(
            ['identifier'   => $modelDTO->identifier],
            [
                'network'                   => $modelDTO->network,
                'networkAccountName'        => $modelDTO->networkAccountName,
                'label'                     => $modelDTO->label,
                'currency_id'               => $modelDTO->currency_id
            ]
        );
        // Always update the balance
        $account->balance = $modelDTO->balance;
        // Only update these attributes if they were null
        if ($account->exists()) {
            if (is_null($account->networkAccountName)) {
                $account->networkAccountName    = $modelDTO->networkAccountName;
            }
            if (is_null($account->customer_id)) {
                $account->customer_id           = $modelDTO->customer_id;
            }
        }
        // Save the updates
        $account->save();

        // Cast the API state to the model
        // $account->state->transitionTo($modelDTO->state);

        return $account;
    }
}
