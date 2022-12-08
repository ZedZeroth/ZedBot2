<?php

/**
 * Unit tests for the AccountSynchronizeRequestAdapterForLCS0 class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\Synchronize\Request\AccountSynchronizeRequestAdapterForLCS0;

/**
 * Testing the buildRequestParameters() method
 */

 // NEUTRAL TEST (LCS doesn't use numberToFetch)
test('GIVEN numberToFetch: -10
WHEN calling buildRequestParameters()
THEN return an AccountSynchronizeRequestAdapterForENM0
', function () {
    $this->assertInstanceOf(
        AccountSynchronizeRequestAdapterForLCS0::class,
        (new AccountSynchronizeRequestAdapterForLCS0())
            ->buildRequestParameters(numberToFetch: -10)
    );
});

/**
 * Testing the fetchResponse() method
 */

// POSITIVE TEST
test('GIVEN a GetAdapterForLCS0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForLCS0())
        ->buildRequestParameters(numberToFetch: -10);

    // Mock a GetAdapter to return a fake request response array
    $getAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS0::class)
        ->shouldReceive('get')
        ->once()
        ->with(
            config('app.ZED_LCS0_WALLETS_ENDPOINT')
        )
        ->andReturn(['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ])
        ->getMock();

    /**
     * Inject the mocked GetAdapter into the RequestAdapter
     * to check that the response array is passed back successfully.
     */
    $this->assertSame(
        ['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ],
        ($builtRequestAdapter)->fetchResponse($getAdapterMock)
    );
});

// Negative
test('GIVEN a GetAdapterForMMP0 with a mocked get() method
WHEN calling fetchResponse()
THEN throw \'...is not an adapter for...\'
', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForLCS0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect Adapter to return a fake request response array
    $getAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForMMP0::class)
        ->shouldReceive('get')
        ->times(0)
        ->with(
            config('app.ZED_LCS0_WALLETS_ENDPOINT')
        )
        ->andReturn(['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ])
        ->getMock();

    // Inject the mocked GetAdapter into the RequestAdapter
    // to check if the response array is passed back successfully.
    $this->assertSame(
        ['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ],
        ($builtRequestAdapter)->fetchResponse($getAdapterMock)
    );
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('is not an adapter for');

// NEGATIVE TEST
test('GIVEN a PostAdapterForENM0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN throw \'...adapter does not contain these methods...\'
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForLCS0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect GetAdapter to return a fake request response array
    $postAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\PostAdapterForENM0::class)
        ->shouldReceive('post')
        ->times(0)
        ->with(
            config('app.ZED_LCS0_WALLETS_ENDPOINT')
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
    $this->assertSame(
        ['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ],
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    );
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('does not contain these methods');
