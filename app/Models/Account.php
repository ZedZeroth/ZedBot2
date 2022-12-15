<?php

declare(strict_types=1);

namespace App\Models;

class Account extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Spatie\ModelStates\HasStates;

    /**
     * The default attributes.
     *
     * @var array<int, string>
     */
    protected /* Do not define */ $attributes = [
        'customer_id' => null,
        'networkAccountName' => null,
        'balance' => null
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => \App\Models\Accounts\States\AccountState::class
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Defines the account's holder.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

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

    /**
     * Returns an HTML table row for the account.
     *
     * @return string
     */
    public function tableRow(): string
    {
        // VALIDATION

        if ($this->customer) {
            $holderHTML = '<td>'
                . $this->customer->linkTo(25)
                . '</td>';
        } else {
            $holderHTML = '<td style="font-style: italic;">'
            . 'NO HOLDER'
            . '</td>';
        }
        $html = '<tr>';
        $html .= '<tr>'
            . '<td><a href="/'
            . $this->network
            . '/accounts">'
            . $this->network
            . '</a></td>'

            . $holderHTML

            . '<td>“' . $this->label . '”</td>'

            . '<td>' . $this->networkAccountName . '</td>'

            . '<td><a href="/account/'
            . $this->identifier
            . '">'
            . $this->identifier
            . '</a></td>'
            . '</tr>';
        return $html;
    }
}
