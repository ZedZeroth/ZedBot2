<?php

declare(strict_types=1);

namespace App\Http\Controllers\Contacts;

class ContactDTO implements
    \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface
{
    /**
     * The contact data transfer object
     * for moving contact data between
     * an adapter and the updater.
     */
    public function __construct(
        public string $state,
        public string $identifier,
        public string $type,
        public string $handle,
        public ?int $customer_id,
    ) {
    }
}
