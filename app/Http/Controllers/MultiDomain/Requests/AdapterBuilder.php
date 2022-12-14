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
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 7,
            longestLength: 7,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $action
        (new \App\Http\Controllers\MultiDomain\Validators\StringValidator())->validate(
            string: $action,
            stringName: 'action',
            source: __FILE__ . ' (' . __LINE__ . ')',
            charactersToRemove: [],
            shortestLength: 6,
            longestLength: 11,
            mustHaveUppercase: true,
            canHaveUppercase: true,
            mustHaveLowercase: true,
            canHaveLowercase: true,
            isAlphabetical: true,
            isNumeric: false,
            isAlphanumeric: true,
            isHexadecimal: false
        );

        // Validate $api
        (new \App\Http\Controllers\MultiDomain\Validators\ApiValidator())
                ->validate(apiCode: $api);

        // Specific request/response path
        $modelActionPath =
            'App\Http\Controllers'
            . '\\' . $model . 's'
            . '\\' . $action;

        // Build the request adaper
        $requestAdapterClass = $modelActionPath
            . '\\Request\\'
            . $model
            . $action
            . 'RequestAdapterFor'
            . strtoupper($api);

        $requestAdapter = new $requestAdapterClass();

        // Build the response adaper
        $responseAdapterClass = $modelActionPath
            . '\\Response\\'
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
                config('app.ZED_APIS_THAT_USE_POST_REQUESTS_FOR_FETCHING')
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
