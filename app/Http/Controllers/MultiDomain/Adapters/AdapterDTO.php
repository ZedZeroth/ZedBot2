<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Adapters;

use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\ResponseAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;

class AdapterDTO
{
    /**
     * The adapter data transfer object
     * for moving adapters from the
     * adapter builder.
     */
    public function __construct(
        public RequestAdapterInterface $requestAdapter,
        public ResponseAdapterInterface $responseAdapter,
        public GeneralAdapterInterface $getOrPostAdapter
    ) {
    }
}
