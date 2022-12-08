<?php

/**
 * Unit tests for the AccountSynchronizeRequestAdapterForENM0 class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\Synchronize\Request\AccountSynchronizeRequestAdapterForENM0;

/**
 * Testing the buildRequestParameters() method
 */

// POSITIVE TEST
test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN return an AccountSynchronizeRequestAdapterForENM0
    ', function () {
    $this->assertInstanceOf(
        AccountSynchronizeRequestAdapterForENM0::class,
        (new AccountSynchronizeRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1)
    );
});

// POSITIVE TEST
test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN return an AccountSynchronizeRequestAdapterForENM0 holding the correct requestParameters
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1);

    // Create a reflection in order to access its private postParameters property
    $property = (new \ReflectionClass($builtRequestAdapter))->getProperty('requestParameters');
    $property->setAccessible(true);

    /**
     * Check that postParameters holds the correct account number
     * and that numberToFetch has been adapted to 'take'.
     */
    $this->assertSame(
        [
            'accountERN' => config('app.ZED_ENM0_ACCOUNT_ERN'),
            'take' => 1
        ],
        $property->getValue($builtRequestAdapter)
    );
});

// NEGATIVE TEST
test('GIVEN numberToFetch: 0
    WHEN calling buildRequestParameters()
    THEN throw an IntegerValidationException
    ', function () {
    $this->assertInstanceOf(
        AccountSynchronizeRequestAdapterForENM0::class,
        (new AccountSynchronizeRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 0)
    );
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\IntegerValidationException::class);

/**
 * Testing the fetchResponse() method
 */

// POSITIVE TEST
test('GIVEN a PostAdapterForENM0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock a PostAdapter to return a fake request response array
    $postAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\PostAdapterForENM0::class)
        ->shouldReceive('post')
        ->once()
        ->with(
            config('app.ZED_ENM0_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => config('app.ZED_ENM0_ACCOUNT_ERN'),
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
    $this->assertSame(
        ['results' =>
            [
                'accountNumber' => '00000000',
                'etc' => 'etc'
            ]
        ],
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    );
});

/* Reinstate when another PostAdapter exists
// Negative
test('GIVEN a PstAdapterForXXX0 with a mocked post() method
WHEN calling fetchResponse()
THEN throw \'...is not an adapter for...\'
', function () {

    ...

->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('is not an adapter for');
*/

// NEGATIVE TEST
test('GIVEN a GetAdapterForLCS with a mocked post() method
    WHEN calling fetchResponse()
    THEN throw \'...adapter does not contain these methods...\'
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizeRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect GetAdapter to return a fake request response array
    $getAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS0::class)
        ->shouldReceive('post')
        ->times(0)
        ->with(
            config('app.ZED_ENM0_BENEFICIARIES_ENDPOINT'),
            [
                'accountERN' => config('app.ZED_ENM0_ACCOUNT_ERN'),
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
->expectExceptionMessage('does not contain these methods');
