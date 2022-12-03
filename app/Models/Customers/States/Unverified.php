<?php

declare(strict_types=1);

namespace App\Models\Customers\States;

class Unverified extends CustomerState
{
    public function getColor(): string
    {
        return '#999';
    }

    public function getEmoji(): string
    {
        return '?';
    }
}
