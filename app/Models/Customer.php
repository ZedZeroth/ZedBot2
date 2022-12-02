<?php

declare(strict_types=1);

namespace App\Models;

class Customer extends \Illuminate\Database\Eloquent\Model
{
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
}
