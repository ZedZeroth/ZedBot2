<?php

declare(strict_types=1);

namespace App\Models;

class Customer extends \Illuminate\Database\Eloquent\Model
{
    use \Spatie\ModelStates\HasStates;

    /**
     * The default attributes.
     *
     * @var array<int, string>
     */
    protected /* Do not define */ $attributes = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state' => \App\Models\Customers\States\CustomerState::class
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the accounts for this customer.
    */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
    * Get the contacts for this customer.
    */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
    * Get the credits for this customer.
    */
    public function credits()
    {
        $credits = collect();
        foreach ($this->accounts()->get() as $account) {
            $credits = $credits->merge($account->credits()->get());
        }
        return $credits;
    }

    /**
    * Get the debits for this customer.
    */
    public function debits()
    {
        $debits = collect();
        foreach ($this->accounts()->get() as $account) {
            $debits = $debits->merge($account->debits()->get());
        }
        return $debits;
    }

    /**
     * Returns their full name
     *
     * @return string
     */
    public function fullName(): string
    {
        $fullName =
            $this->familyName . ', '
            . $this->givenName1;

        if ($this->givenName2) {
            $fullName .= ' ' . $this->givenName2;
        }

        return $fullName;
    }

    /**
     * Returns an HTML link to a customer.
     *
     * @param int $length
     * @return string
     */
    public function linkTo(
        int $length
    ): string {
        // VALIDATION

        $name = (
            new \App\Http\Controllers\MultiDomain\Html\HtmlStringShortener())
            ->shorten(
                $this->familyName . ', ' . $this->givenName1,
                $length
            );
        return '🗿 <a href="/customer/'
            . $this->identifier
            . '">'
            . $name
            . '</a>';
    }
}
