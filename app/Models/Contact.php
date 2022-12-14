<?php

declare(strict_types=1);

namespace App\Models;

class Contact extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Returns an emoji for the contact type.
     *
     * @return string
     */
    public function emoji(): string
    {
        return match ($this->type) {
            'email' => '📧',
            'phone' => '📞',
        };
    }
}
