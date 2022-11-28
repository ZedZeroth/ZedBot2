<?php

declare(strict_types=1);

namespace App\Http\Controllers\Accounts;

class AccountDTO
{
    /**
     * The account data transfer object
     * for moving account data between
     * an adapter and the synchronizer.
     */
    public function __construct(
        public string $network,
        public string $identifier,
        public int $customer_id,
        public string $networkAccountName,
        public string $label,
        public int $currency_id,
        public int $balance,
    ) {
    }
}
