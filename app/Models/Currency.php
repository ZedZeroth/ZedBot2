<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the payments in this currency.
    */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
    * Get the accounts in this currency.
    */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
