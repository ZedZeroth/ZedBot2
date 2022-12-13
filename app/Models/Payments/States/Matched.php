<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class Matched extends PaymentState
{
    public function getColor(): string
    {
        return '#0C0';
    }

    public function getEmoji(): string
    {
        return '💰🔗⏳';
    }
}
