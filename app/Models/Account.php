<?php

declare(strict_types=1);

namespace App\Models;

class Account extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The default attributes.
     *
     * @var array<int, string>
     */
    protected /* Do not define */ $attributes = [
        'networkAccountName' => '',
        'label' => ''
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the incoming payments for this account.
    */
    public function credits()
    {
        return $this->hasMany(Payment::class, 'beneficiary_id');
    }

    /**
    * Get the outgoing payments for this account.
    */
    public function debits()
    {
        return $this->hasMany(Payment::class, 'originator_id');
    }

    /**
    * Determine the currency for this account.
    */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Formats the account balance into
     * its standard denomination.
     *
     * @return string
     */
    public function formatBalance()
    {
        return (new \App\Http\Controllers\MultiDomain\Money\MoneyFormatter())
        ->format(
            amount: $this->balance,
            currency: $this->currency()->first()
        );
    }
}
