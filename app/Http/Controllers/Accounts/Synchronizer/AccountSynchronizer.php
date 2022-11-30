<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronizer;

use App\Models\Account;

class AccountSynchronizer implements
    \App\Http\Controllers\MultiDomain\Interfaces\SynchronizerInterface
{
    /**
     * Uses the DTOs to create accounts for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function sync(
        array $modelDTOs
    ): void {
        foreach ($modelDTOs as $accountDTO) {
            //Validate DTOs
            (new \App\Http\Controllers\MultiDomain\Validators\DtoValidator())
                ->validate(
                    dto: $accountDTO,
                    dtoName: 'accountDTO',
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

            // Create accounts
            Account::firstOrCreate(
                ['identifier' => $accountDTO->identifier],
                [
                    'network' => $accountDTO->network,
                    'customer_id' => $accountDTO->customer_id,
                    'currency_id' => $accountDTO->currency_id,
                    'balance' => $accountDTO->balance,
                ]
            );

            // If a networkAccountName is passed then update it
            if ($accountDTO->networkAccountName) {
                Account::where('identifier', $accountDTO->identifier)
                ->update(['networkAccountName' => $accountDTO->networkAccountName]);
            }

            // If a label is passed then update it
            if ($accountDTO->label) {
                Account::where('identifier', $accountDTO->identifier)
                ->update(['label' => $accountDTO->label]);
            } else {
                Account::where('identifier', $accountDTO->identifier)
                ->update(['label' => '[NO LABEL FOUND]']);
            }
        }

        return;
    }
}
