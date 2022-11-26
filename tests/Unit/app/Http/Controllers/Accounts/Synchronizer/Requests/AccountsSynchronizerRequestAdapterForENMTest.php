<?php

/**
 * Unit tests for the AccountsSynchronizerRequestAdapterForENM class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\Synchronizer\Requests\AccountsSynchronizerRequestAdapterForENM;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

test('GIVEN numberToFetch: 1
    WHEN calling buildPostParameters()
    THEN a RequestAdapterInterface is returned with valid postParameters
    ', function () {

    $returned = (new AccountsSynchronizerRequestAdapterForENM())
            ->buildPostParameters(numberToFetch: 1);

    expect($returned)->toBeInstanceOf(RequestAdapterInterface::class);

    $reflection = new \ReflectionClass($returned);
    $reflection_property = $reflection->getProperty('postParameters');
    $reflection_property->setAccessible(true);

    expect(
        $reflection_property->getValue($returned)
    )->toMatchArray([
        'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
        'take' => 1
    ]);
});
