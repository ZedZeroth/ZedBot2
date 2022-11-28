<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Requests;

class AdapterDTO
{
    /**
     * The adapter data transfer object
     * for moving adapters from the
     * adapter builder.
     */
    public function __construct(
        public \App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface $requestAdapter,
        public \App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface $responseAdapter,
        public \App\Http\Controllers\MultiDomain\Interfaces\GetOrPostAdapterInterface $getOrPostAdapter
    ) {
    }
}
