<?php

declare(strict_types=1);

namespace App\Models\Customers\States;

abstract class CustomerState extends \Spatie\ModelStates\State
{
    abstract public function getColor(): string;
    abstract public function getEmoji(): string;

    public static function config(): \Spatie\ModelStates\StateConfig
    {
        return parent::config()

            ->default(Unverified::class)
            ->allowTransition(Unverified::class, Unverified::class)
        /*
            // Anything  -> Banned
            ->allowTransition(Banned::class, Banned::class)
            ->allowTransition(Unverified::class, Banned::class)
            ->allowTransition(Active::class, Banned::class)
            ->allowTransition(Suspended::class, Banned::class)

            // Unverified  ->   Active
            // Suspended
            ->allowTransition(Active::class, Active::class)
            ->allowTransition(Unverified::class, Active::class)
            ->allowTransition(Suspended::class, Active::class)

            // Active  ->   Suspended
            ->allowTransition(Suspended::class, Suspended::class)
            ->allowTransition(Active::class, Suspended::class)
        */
        ;
    }
}
