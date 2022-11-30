<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

class Reciprocated extends PaymentState
{
    public function getColor(): string
    {
        return '#00F';
    }

    public function getEmoji(): string
    {
        return '┣━▶ 🫱🏽‍🫲🏿';
    }
}
