<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class AmountError extends PaymentState
{
    public function getColor(): string
    {
        return '#F00';
    }

    public function getEmoji(): string
    {
        return '🫘🫘🫘';
    }
}
