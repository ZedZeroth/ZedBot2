<?php

declare(strict_types=1);

namespace App\Models\Payments\States;

abstract class PaymentState extends \Spatie\ModelStates\State
{
    abstract public function getColor(): string;
    abstract public function getEmoji(): string;

    public static function config(): \Spatie\ModelStates\StateConfig
    {
        return parent::config()
            ->default(Unconfirmed::class)
            ->allowTransition(Unconfirmed::class, Unconfirmed::class)

            // Everything except Reciprocated  -> Held     -> Unconfirmed
            ->allowTransition(Held::class, Held::class)
            ->allowTransition(Unconfirmed::class, Held::class)
            ->allowTransition(Settled::class, Held::class)
            ->allowTransition(AmountError::class, Held::class)
            ->allowTransition(OriginatorError::class, Held::class)
            ->allowTransition(AmountError::class, Held::class)
            ->allowTransition(Matched::class, Held::class)
            ->allowTransition(Held::class, Unconfirmed::class)
            ->allowTransition(Held::class, Settled::class)

            // Unconfirmed  -> Settled   -> AmountError
            //                           -> OriginatorError
            //                           -> Matched (manual if incorrect reference)
            ->allowTransition(Unconfirmed::class, Settled::class)
            ->allowTransition(Settled::class, Settled::class)
            ->allowTransition(Settled::class, AmountError::class)
            ->allowTransition(Settled::class, OriginatorError::class)
            ->allowTransition(Settled::class, Matched::class)

            // AmountError           -> Matched (manual once resolved)
            // OriginatorError       ->
            ->allowTransition(AmountError::class, AmountError::class)
            ->allowTransition(AmountError::class, Matched::class)
            ->allowTransition(OriginatorError::class, OriginatorError::class)
            ->allowTransition(OriginatorError::class, Matched::class)

            // Matched  -> Reciprocated
            ->allowTransition(Matched::class, Matched::class)
            ->allowTransition(Matched::class, Reciprocated::class)
            ->allowTransition(Reciprocated::class, Reciprocated::class)
        ;
    }
}
