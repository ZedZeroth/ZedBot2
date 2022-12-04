<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts\Synchronize;

use App\Models\Account;

class AccountSynchronizer
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

            // Has DTO data been validated...? Pulled straight from API?

            // Accounts must be manually assigned to their holder?

            // Create accounts
            Account::firstOrCreate(
                ['identifier' => $accountDTO->identifier],
                [
                    'network'       => $accountDTO->network,
                    'customer_id'     => 1,
                    'currency_id'   => $accountDTO->currency_id,
                    'balance'       => $accountDTO->balance,
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
