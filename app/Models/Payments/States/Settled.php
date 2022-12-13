<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class Settled extends PaymentState
{
    public function getColor(): string
    {
        return '#C90';
    }

    public function getEmoji(): string
    {
        return '💰⏳⏳';
    }
}
