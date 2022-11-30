<?php

/**
 * Unit tests for the AccountSynchronizerRequestAdapterForENM0 class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\MultiDomain\Interfaces\AdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;

/**
 * Testing the buildRequestParameters() method
 */

test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN an AccountSynchronizerRequestAdapterForENM0 is returned
    ')
    ->expect(fn() => (
        new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1))
    ->toBeInstanceOf(AccountSynchronizerRequestAdapterForENM0::class);

test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN the returned AccountSynchronizerRequestAdapterForENM0 holds the correct postParameters
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1);

    // Create a reflection in order to access its private postParameters property
    $property = (new \ReflectionClass($builtRequestAdapter))->getProperty('postParameters');
    $property->setAccessible(true);

    /**
     * Check that postParameters holds the correct account number
     * and that numberToFetch has been adapted to 'take'.
     */
    expect(
        $property->getValue($builtRequestAdapter)
    )->toMatchArray([
        'accountERN' => config('app.ZED_ENM_ACCOUNT_ERN'),
        'take' => 1
    ]);
});

test('GIVEN numberToFetch: 0
    WHEN calling buildRequestParameters()
    THEN an IntegerValidationException is thrown
    ')
    ->expectException(IntegerValidationException::class)
    ->expect(fn() => (
        new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 0))
    ->toBeInstanceOf(AccountSynchronizerRequestAdapterForENM0::class);

/**
 * Testing the fetchResponse() method
 */

test('GIVEN a PostAdapterForENM0 with a mocked post() method 
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock a PostAdapter to return a fake request response array
    $postAdapterMock = partialMock(\App\Http\Controllers\MultiDomain\Requests\PostAdapterForENM0::class)
        ->shouldReceive('post')
        ->with(
            endpoint: config('app.ZED_ENM_BENEFICIARIES_ENDPOINT'),
            requestParameters: [
                'accountERN' => config('app.ZED_ENM_ACCOUNT_ERN'),
                'take' => 1
            ]
        )
        ->andReturn(['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ])
        ->getMock();

    /**
     * Inject the mocked PostAdapter into the RequestAdapter
     * to check that the response array is passed back successfully.
     */
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
});

test('GIVEN a GetAdapterForLCS0 with a mocked post() method 
    WHEN calling fetchResponse()
    THEN \'...is not an adapter for...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect Adapter to return a fake request response array
    $postAdapterMock = partialMock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS0::class)
        ->shouldReceive('post')
        ->with(
            config('app.ZED_ENM_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => config('app.ZED_ENM_ACCOUNT_ERN'),
                'take' => 1
            ]
        )
        ->andReturn(['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ])
        ->getMock();

    /**
     * Inject the mocked PostAdapter into the RequestAdapter
     * to check if the response array is passed back successfully.
     */
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
})->expectExceptionMessage('is not an adapter for');
//->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)

test('GIVEN a GetAdapterForLCS with a mocked post() method 
    WHEN calling fetchResponse()
    THEN \'...adapter does not contain these methods...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect GetAdapter to return a fake request response array
    $postAdapterMock = partialMock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS0::class)
        ->shouldReceive('post')
        ->with(
            config('app.ZED_ENM_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => config('app.ZED_ENM_ACCOUNT_ERN'),
                'take' => 1
            ]
        )
        ->andReturn(['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ])
        ->getMock();

    /**
     * Inject the mocked PostAdapter into the RequestAdapter
     * to check if the response array is passed back successfully.
     */
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
})->expectExceptionMessage('adapter does not contain these methods');
