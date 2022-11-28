<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Adapters;

class AdapterBuilder
{
    /**
     * Builds the correct adapters for
     * the specified API platform.
     *
     * @param string $models
     * @param string $action
     * @param string $provider
     * @return AdapterDTO
     */
    public function build(
        string $models,
        string $action,
        string $provider
    ): AdapterDTO {

        // Specific request/response path
        $modelActionPath =
            'App\Http\Controllers'
            . '\\' . $models
            . '\\' . $action;

        // Build the request adaper
        $requestAdapterClass = $modelActionPath
            . '\\Requests\\'
            . $models
            . $action
            . 'RequestAdapterFor'
            . strtoupper($provider);

        $requestAdapter = new $requestAdapterClass();

        // Build the response adaper
        $responseAdapterClass = $modelActionPath
            . '\\Responses\\'
            . $models
            . $action
            . 'ResponseAdapterFor'
            . strtoupper($provider);
        $responseAdapter = new $responseAdapterClass();

        // Build the general get/post adapter
        $generalPath = 'App\Http\Controllers\MultiDomain\Adapters';
        if (
            in_array(
                strtoupper($provider),
                explode(',', env('ZED_APIS_THAT_USE_POST_REQUESTS_FOR_FETCHING'))
            )
        ) {
            $getOrPostAdapterClass = $generalPath
                . '\PostAdapterFor'
                . strtoupper($provider);
        } else {
            $getOrPostAdapterClass = $generalPath
                . '\GetAdapterFor'
                . strtoupper($provider);
        }
        $getOrPostAdapter = new $getOrPostAdapterClass();

        return new AdapterDTO(
            requestAdapter: $requestAdapter,
            responseAdapter: $responseAdapter,
            getOrPostAdapter: $getOrPostAdapter,
        );
    }
}
