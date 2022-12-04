<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Accounts\AccountDTO;

class PaymentDTO
{
    /**
     * The payment data transfer object
     * for moving payment data between
     * an adapter and the synchronizer.
     */
    public function __construct(
        public string $state,
        public string $network,
        public string $identifier,
        public int $amount,
        public int $currency_id,
        public string $originator_identifier,
        public string $beneficiary_identifier,
        public string $memo,
        public string $timestamp,
        public AccountDTO $originatorAccountDTO,
        public AccountDTO $beneficiaryAccountDTO,
    ) {
    }
}
