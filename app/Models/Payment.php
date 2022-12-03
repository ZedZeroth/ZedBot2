<?php

declare(strict_types=1);

namespace App\Models;

class Payment extends \Illuminate\Database\Eloquent\Model
{
    use \Spatie\ModelStates\HasStates;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => \App\Models\Payments\States\PaymentState::class
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
     * Defines the payment's currency.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Defines the originator account.
     */
    public function originator()
    {
        return $this->belongsTo(Account::class, 'originator_id');
    }

    /**
     * Defines the beneficiary account.
     */
    public function beneficiary()
    {
        return $this->belongsTo(Account::class, 'beneficiary_id');
    }

    /**
     * Formats the account balance into
     * its standard denomination.
     *
     * @return string
     */
    public function formatAmount(): string
    {
        return (new \App\Http\Controllers\MultiDomain\Money\MoneyFormatter())
        ->format(
            amount: $this->amount,
            currency: $this->currency()->first()
        );
    }
}
