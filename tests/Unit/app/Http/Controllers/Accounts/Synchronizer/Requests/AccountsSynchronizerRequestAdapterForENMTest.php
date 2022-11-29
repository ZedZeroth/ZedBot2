<?php

/**
 * Unit tests for the AccountsSynchronizerRequestAdapterForENM class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\Synchronizer\Requests\AccountsSynchronizerRequestAdapterForENM;
use App\Http\Controllers\MultiDomain\Validators\IntegerValidationException;
use App\Http\Controllers\MultiDomain\Requests\PostAdapterForENM;
use App\Http\Controllers\MultiDomain\Requests\PostAdapterForENMF;
use App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS;
use App\Http\Controllers\MultiDomain\Validators\AdapterValidationException;

/**
 * Testing the buildRequestParameters() method
 */

test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN an AccountsSynchronizerRequestAdapterForENM is returned
    ')
    ->expect(fn() => (
        new AccountsSynchronizerRequestAdapterForENM())
            ->buildRequestParameters(numberToFetch: 1)
    )
    ->toBeInstanceOf(AccountsSynchronizerRequestAdapterForENM::class);

test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN the returned AccountsSynchronizerRequestAdapterForENM holds the correct postParameters
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM())
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
        'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
        'take' => 1
    ]);
});

test('GIVEN numberToFetch: 0
    WHEN calling buildRequestParameters()
    THEN an IntegerValidationException is thrown
    ')
    ->expectException(IntegerValidationException::class)
    ->expect(fn() => (
        new AccountsSynchronizerRequestAdapterForENM())
            ->buildRequestParameters(numberToFetch: 0)
    )
    ->toBeInstanceOf(AccountsSynchronizerRequestAdapterForENM::class);

/**
 * Testing the fetchResponse() method
 */

test('GIVEN a PostAdapterForENM with a mocked post() method 
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock a PostAdapter to return a fake request response array
    $postAdapterMock = mock(PostAdapterForENM::class)
        ->shouldReceive('post')
        ->with(
            env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
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

test('GIVEN a PostAdapterForENMF with a mocked post() method 
    WHEN calling fetchResponse()
    THEN \'...is not an adapter for...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect PostAdapter to return a fake request response array
    $postAdapterMock = mock(PostAdapterForENMF::class)
        ->shouldReceive('post')
        ->with(
            env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
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

test('GIVEN a GetAdapterForLCS with a mocked post() method 
    WHEN calling fetchResponse()
    THEN \'...adapter does not contain these methods...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountsSynchronizerRequestAdapterForENM())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect GetAdapter to return a fake request response array
    $postAdapterMock = mock(GetAdapterForLCS::class)
        ->shouldReceive('post')
        ->with(
            env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
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