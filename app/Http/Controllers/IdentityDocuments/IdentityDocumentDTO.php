<?php

declare(strict_types=1);

namespace App\Http\Controllers\IdentityDocuments;

class IdentityDocumentDTO implements
    \App\Http\Controllers\MultiDomain\Interfaces\ModelDtoInterface
{
    /**
     * The identity document data transfer object
     * for moving identity document data between
     * an adapter and the updater.
     */
    public function __construct(
        public string $state,
        public string $identifier,
        public string $type,
        public ?string $nationality,
        public ?string $placeOfBirth, // nullable while importing
        public ?string $dateOfBirth, // nullable while importing
        public ?string $dateOfExpiry, // nullable while importing
        public ?int $customer_id,
    ) {
    }
}
