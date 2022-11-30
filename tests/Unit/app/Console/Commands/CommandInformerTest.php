<?php

/**
 * Unit tests for the CommandInformerTest class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\CommandInformer;

/**
 * Testing the run() method
 */

test('GIVEN a correctly mocked Command
    WHEN calling run()
    THEN it returns null
    ', function () {

    // Mock a Command to return a fake request response array
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('cli')
        ->shouldReceive('info')->with('[📟] test:test')->andReturn()
        ->shouldReceive('info')->with('---------------------------------')->andReturn()
        ->shouldReceive('argument')->with()->andReturn([])
        ->shouldReceive('info')->with('... Running "test:test"')->andReturn()
        ->shouldReceive('runThisCommand')->with()->andReturn()
        ->shouldReceive('info')->with('... 0ms DONE')->andReturn()
        ->shouldReceive('info')->with('No new models created.')->andReturn()
        ->shouldReceive('info')->with('')->andReturn()
        ->getMock();

    // Inject the mock into a new CommandInformer and call run()
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
});

test('GIVEN a mocked Command with "command" argument "t:t"
    WHEN calling run()
    THEN a StringValidationException is thrown
    ', function () {

    // Mock a Command to return a fake request response array
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('t:t')
        ->getMock();

    // Inject the mock into a new CommandInformer and call run()
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\StringValidationException::class);

test('GIVEN a mocked Command with "source" argument "test"
    WHEN calling run()
    THEN a CommandValidationException is thrown
    ', function () {

    // Mock a Command to return a fake request response array
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('test')
        ->getMock();

    // Inject the mock into a new CommandInformer and call run()
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
})
->expectException(\App\Console\Commands\CommandValidationException::class);











/*
test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN an AccountSynchronizerRequestAdapterForENM0 is returned
    ', function () {
    expect(
        (new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1)
    )->toBeInstanceOf(
        AccountSynchronizerRequestAdapterForENM0::class
    );
});

test('GIVEN numberToFetch: 1
    WHEN calling buildRequestParameters()
    THEN the returned AccountSynchronizerRequestAdapterForENM0 holds the correct requestParameters
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 1);

    // Create a reflection in order to access its private postParameters property
    $property = (new \ReflectionClass($builtRequestAdapter))->getProperty('requestParameters');
    $property->setAccessible(true);

    // Check that postParameters holds the correct account number
    // and that numberToFetch has been adapted to 'take'.
    expect(
        $property->getValue($builtRequestAdapter)
    )->toMatchArray([
        'accountERN' => config('app.ZED_ENM0_ACCOUNT_ERN'),
        'take' => 1
    ]);
});

test('GIVEN numberToFetch: 0
    WHEN calling buildRequestParameters()
    THEN an IntegerValidationException is thrown
    ', function () {
    expect(
        (new AccountSynchronizerRequestAdapterForENM0())
            ->buildRequestParameters(numberToFetch: 0)
    )->toBeInstanceOf(
        AccountSynchronizerRequestAdapterForENM0::class
    );
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\IntegerValidationException::class);

test('GIVEN a PostAdapterForENM0 with a mocked post() method
    WHEN calling fetchResponse()
    THEN the received response array is passed up and returned
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock a PostAdapter to return a fake request response array
    $postAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\PostAdapterForENM0::class)
        ->shouldReceive('post')
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

    // Inject the mocked PostAdapter into the RequestAdapter
    // to check that the response array is passed back successfully.
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
});

test('GIVEN a POST_ADAPTER with a mocked post() method
    WHEN calling fetchResponse()
    THEN \'...is not an adapter for...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect Adapter to return a fake request response array
    $postAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\POST_ADAPTER::class)
        ->shouldReceive('post')
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

    // Inject the mocked PostAdapter into the RequestAdapter
    // to check if the response array is passed back successfully.
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('is not an adapter for');


test('GIVEN a GetAdapterForLCS with a mocked post() method
    WHEN calling fetchResponse()
    THEN \'...adapter does not contain these methods...\' is thrown
    ', function () {

    // Build the RequestAdapter's postParameters property
    $builtRequestAdapter = (new AccountSynchronizerRequestAdapterForENM0())
        ->buildRequestParameters(numberToFetch: 1);

    // Mock an incorrect GetAdapter to return a fake request response array
    $postAdapterMock = mock(\App\Http\Controllers\MultiDomain\Requests\GetAdapterForLCS0::class)
        ->shouldReceive('post')
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

    // Inject the mocked PostAdapter into the RequestAdapter
    // to check if the response array is passed back successfully.
    expect(
        ($builtRequestAdapter)->fetchResponse($postAdapterMock)
    )->toMatchArray(['results' =>
        [
            'accountNumber' => '00000000',
            'etc' => 'etc'
        ]
    ]);
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\AdapterValidationException::class)
->expectExceptionMessage('does not contain these methods');

*/
