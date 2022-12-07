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
}
