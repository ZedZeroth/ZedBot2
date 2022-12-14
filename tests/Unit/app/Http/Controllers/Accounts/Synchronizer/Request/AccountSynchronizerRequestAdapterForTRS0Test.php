<?php

/**
 * Unit tests for the AccountSynchronizeRequestAdapterForTRS0 class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\Synchronize\Request\AccountSynchronizeRequestAdapterForTRS0;

/**
 * Testing the buildRequestParameters() method
 */

 // NEUTRAL TEST (TRS doesn't use numberToFetch)
test('GIVEN numberToFetch: -10
WHEN calling buildRequestParameters()
THEN return an AccountSynchronizeRequestAdapterForTRS0
', function () {
    $this->assertInstanceOf(
        AccountSynchronizeRequestAdapterForTRS0::class,
        (new AccountSynchronizeRequestAdapterForTRS0())
            ->buildRequestParameters(numberToFetch: -10)
    );
});

/**
 * Testing the fetchResponse() method
 */

// POSITIVE TEST
test('GIVEN a GetAdapterForTRS0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForTRS0())
        ->buildRequestParameters(numberToFetch: -10);

    // Build the address arguments
    $addressDetailsCollection =
        \App\Models\Account::where('network', 'Tron')
            ->get();

    $argumentArray = [];
    $returnArray = [];
    $responseArray = [
        'balance' => 1,
        'etc' => 'etc'
    ];

    // Mock a GetAdapter to return a fake request response array
    $getAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForTRS0::class);

    // Build arrays and chain responses (as ->withArgs($argumentArray) is not working...?)
    foreach ($addressDetailsCollection->all() as $addressDetails) {
        $getAdapterMock = $getAdapterMock
            ->shouldReceive('get')
            ->once()
            ->with(
                config('app.ZED_TRS0_ADDRESS_ENDPOINT')
                    . $addressDetails->networkAccountName
            )
            ->andReturn($responseArray)
            ->getMock();
        array_push(
            $returnArray,
            [
                'label' => $addressDetails->label,
                'response' => $responseArray
            ]
        );
    }

    /**
     * Inject the mocked GetAdapter into the RequestAdapter
     * to check that the response array is passed back successfully.
     */

    $this->assertSame(
        $returnArray,
        ($builtRequestAdapter)->fetchResponse($getAdapterMock)
    );
})->group('slow', 'requiresModels');

// NOTE: Test invalid addresses at the validator-level as
// account attributes can't be easily modified here.

// Negative
test('GIVEN a GetAdapterForMMP0 with a mocked get() method
WHEN calling fetchResponse()
THEN throw \'...is not an adapter for...\'
', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForTRS0())
        ->buildRequestParameters(numberToFetch: 1);

    // Build the address arguments
    $addressDetailsCollection =
        \App\Models\Account::where('network', 'Bitcoin')
            ->get();

    $argumentArray = [];
    $returnArray = [];
    $responseArray = [
        'balance' => 1,
        'etc' => 'etc'
    ];

    // Mock a GetAdapter to return a fake request response array
    $getAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForMMP0::class);

    // Build arrays and chain responses (as ->withArgs($argumentArray) is not working...?)
    foreach ($addressDetailsCollection->all() as $addressDetails) {
        $getAdapterMock = $getAdapterMock
            ->shouldReceive('get')
            ->never()
            ->with(
                config('app.ZED_MMP0_ADDRESS_ENDPOINT')
                    . $addressDetails->networkAccountName
            )
            ->andReturn($responseArray)
            ->getMock();
        array_push(
            $returnArray,
            [
                'label' => $addressDetails->label,
                'response' => $responseArray
            ]
        );
    }

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
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('is not an adapter for')
->group('requiresModels');

// NEGATIVE TEST
test('GIVEN a PostAdapterForENM0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN throw \'...adapter does not contain these methods...\'
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForTRS0())
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
