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
     * @param array $modelDTOs
     * @param AccountUpdater $accountUpdater
     */
    public function sync(
        array $modelDTOs,
        \App\Http\Controllers\Accounts\Update\AccountUpdater $accountUpdater
    ): bool {
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

            // Create account
            $accountUpdater->update($accountDTO);
        }

        return true;
    }
}
