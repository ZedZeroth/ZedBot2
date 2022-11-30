<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class Unconfirmed extends PaymentState
{
    public function getColor(): string
    {
        return '#999';
    }

    public function getEmoji(): string
    {
        return '┋&nbsp;&nbsp;&nbsp;💱';
    }
}
