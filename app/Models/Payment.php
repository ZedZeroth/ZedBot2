<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\MultiDomain\Money\MoneyFormatter;

class Payment extends Model
{
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
        return (new MoneyFormatter())->format(
            amount: $this->amount,
            currency: $this->currency()->first()
        );
    }
}
