<?php

declare(strict_types=1);

namespace App\Http\Controllers\MultiDomain\Requests;

class AdapterBuilder
{
    /**
     * Builds the correct adapters for
     * the specified API platform.
     *
     * @param string $model
     * @param string $action
     * @param string $api
     * @return AdapterDTO
     */
    public function build(
        string $model,
        string $action,
        string $api
    ): AdapterDTO {

        // Validate $model
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $model,
            stringName: 'model',
            shortestLength: 7,
            longestLength: 7,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate $action
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $action,
            stringName: 'action',
            shortestLength: 12,
            longestLength: 12,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true
        );

        // Validate $api
        (new \App\Http\Controllers\MultiDomain\Validators\APIValidator())
                ->validate(apiCode: $api);

        // Specific request/response path
        $modelActionPath =
            'App\Http\Controllers'
            . '\\' . $model . 's'
            . '\\' . $action;

        // Build the request adaper
        $requestAdapterClass = $modelActionPath
            . '\\Requests\\'
            . $model
            . $action
            . 'RequestAdapterFor'
            . strtoupper($api);

        $requestAdapter = new $requestAdapterClass();

        // Build the response adaper
        $responseAdapterClass = $modelActionPath
            . '\\Responses\\'
            . $model
            . $action
            . 'ResponseAdapterFor'
            . strtoupper($api);
        $responseAdapter = new $responseAdapterClass();

        // Build the general get/post adapter
        $generalPath = 'App\Http\Controllers\MultiDomain\Requests';
        if (
            in_array(
                strtoupper($api),
                explode(',', env('ZED_APIS_THAT_USE_POST_REQUESTS_FOR_FETCHING'))
            )
        ) {
            $getOrPostAdapterClass = $generalPath
                . '\PostAdapterFor'
                . strtoupper($api);
        } else {
            $getOrPostAdapterClass = $generalPath
                . '\GetAdapterFor'
                . strtoupper($api);
        }
        $getOrPostAdapter = new $getOrPostAdapterClass();

        return new AdapterDTO(
            requestAdapter: $requestAdapter,
            responseAdapter: $responseAdapter,
            getOrPostAdapter: $getOrPostAdapter,
        );
    }
}
