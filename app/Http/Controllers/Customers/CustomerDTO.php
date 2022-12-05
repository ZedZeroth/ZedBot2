<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customers;

class CustomerDTO implements
    \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface
{
    /**
     * The account data transfer object
     * for moving customer data between
     * an adapter and the importer.
     */
    public function __construct(
        public string $state,
        public string $identifier,
        public string $type,
        public string $familyName,
        public string $givenName1,
        public ?string $givenName2,
        public ?string $companyName,
        public ?string $preferredName,
        public array $accountDTOs,
    ) {
    }
}
