<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class Held extends PaymentState
{
    public function getColor(): string
    {
        return '#000';
    }

    public function getEmoji(): string
    {
        return '🏦🏦🏦';
    }
}
