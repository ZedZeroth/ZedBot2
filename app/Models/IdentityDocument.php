<?php

declare(strict_types=1);

namespace App\Models;

class IdentityDocument extends \Illuminate\Database\Eloquent\Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected /* Do not define */ $guarded = [];

    /**
    * Get the owner (customer) of this identity document.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Returns an emoji for the identity document type.
     *
     * @return string
     */
    public function emoji(): string
    {
        return match ($this->type) {
            'brp' => '🪪',
            'dl' => '🚗',
            'pp' => '🛂',
        };
    }
}
