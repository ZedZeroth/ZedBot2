<?php

/**
 * Unit tests for the ExceptionCatcher class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\ExceptionCatcher;

/**
 * Testing the catch() method
 */

test('GIVEN a correctly mocked Command
    WHEN calling catch()
    THEN it returns true
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('cli')
        ->shouldReceive('argument')->with()->andReturn([])
        ->getMock();

    // Inject the mock into a new ExceptionCatcher's validate() method
    expect(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    )->toThrow(\Exception::class);
});

/*
test('GIVEN an invalid source
    WHEN calling validate()
    THEN a CommandValidationException is thrown
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('source')->andReturn('test')
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandValidator())->validate(
            $commandMock,
            'test:test'
        )
    )->toBeTrue();
})
->expectException(\App\Console\Commands\CommandValidationException::class);

test('GIVEN an invalid API
    WHEN calling validate()
    THEN a CommandValidationException is thrown
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('source')->andReturn('scheduler')
        ->shouldReceive('argument')->with()->andReturn(['API' => 'XXX0'])
        ->shouldReceive('argument')->with('API')->andReturn('XXX0')
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandValidator())->validate(
            $commandMock,
            'test:test'
        )
    )->toBeTrue();
})->expectException(\App\Http\Controllers\MultiDomain\Validators\ApiValidationException::class);


test('GIVEN an invalid "Number to fetch"
    WHEN calling validate()
    THEN a CommandValidationException is thrown
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('source')->andReturn('scheduler')
        ->shouldReceive('argument')->with()->andReturn(['Number to fetch' => -1])
        ->shouldReceive('argument')->with('Number to fetch')->andReturn(-1)
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandValidator())->validate(
            $commandMock,
            'test:test'
        )
    )->toBeTrue();
})->expectException(\App\Http\Controllers\MultiDomain\Validators\IntegerValidationException::class);
*/
