<?php

declare(strict_types=1);

namespace App\Models\Accounts\States;

abstract class AccountState extends \Spatie\ModelStates\State
{
    abstract public function getColor(): string;
    abstract public function getEmoji(): string;

    public static function config(): \Spatie\ModelStates\StateConfig
    {
        return parent::config()
        /*
            ->default(Unverified::class)
            ->allowTransition(Unverified::class, Unverified::class)

            // Anything  -> Blocked
            ->allowTransition(Blocked::class, Blocked::class)
            ->allowTransition(Unverified::class, Blocked::class)
            ->allowTransition(Active::class, Blocked::class)
            ->allowTransition(Inactive::class, Blocked::class)

            // Unverified  -> Active
            ->allowTransition(Unverified::class, Active::class)
            ->allowTransition(Active::class, Active::class)

            // Active  -> Inactive
            ->allowTransition(Inactive::class, Inactive::class)
            ->allowTransition(Active::class, Inactive::class)
        */
        ;
    }
}
