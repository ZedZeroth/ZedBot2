<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

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
        public int $originator_id,
        public int $beneficiary_id,
        public string $memo,
        public string $timestamp,
    ) {
    }
}
