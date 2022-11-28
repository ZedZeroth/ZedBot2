<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\MultiDomain\Interfaces\SynchronizerInterface;
use App\Models\Account;

class AccountSynchronizer implements SynchronizerInterface
{
    /**
     * Uses the DTOs to create accounts for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function sync(
        array $DTOs
    ): void {
        foreach ($DTOs as $dto) {
            Account::firstOrCreate(
                ['identifier' => $dto->identifier],
                [
                    'network' => $dto->network,
                    'customer_id' => $dto->customer_id,
                    'currency_id' => $dto->currency_id,
                    'balance' => $dto->balance,
                ]
            );

            // If a networkAccountName is passed then update it
            if ($dto->networkAccountName) {
                Account::where('identifier', $dto->identifier)
                ->update(['networkAccountName' => $dto->networkAccountName]);
            }

            // If a label is passed then update it
            if ($dto->label) {
                Account::where('identifier', $dto->identifier)
                ->update(['label' => $dto->label]);
            }
        }

        return;
    }
}
