<?php

declare(strict_types=1);

namespace App\Models;

class Contact extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the owner (customer) of this contact.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
