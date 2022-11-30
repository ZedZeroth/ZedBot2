<?php

/**
 * Unit tests for the CommandInformer class and its methods.
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

    // Mock a Command
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

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
});

test('GIVEN a mocked Command with "command" argument "t:t"
    WHEN calling run()
    THEN a StringValidationException is thrown
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('t:t')
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\StringValidationException::class);

test('GIVEN a mocked Command with "source" argument "test"
    WHEN calling run()
    THEN a CommandValidationException is thrown
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('test')
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
})
->expectException(\App\Console\Commands\CommandValidationException::class);

/**
 * Testing the output() method
 */

test('GIVEN a correctly mocked Command
    WHEN calling ouput("[📟] test:test")
    THEN it returns null
    ', function () {
    // Mock a Command
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

    // Inject the mock into a new CommandInformer's run() method
    $injectedCommandInformer = new CommandInformer();
    $injectedCommandInformer->run($commandMock);

    // Call output('test') on the CommandInformer
    expect(
        $injectedCommandInformer->output('[📟] test:test')
    )->toBeNull();
});

test('GIVEN a string of length 1000
    WHEN calling ouput()
    THEN a StringValidationException is thrown
    ', function () {
    // Mock a Command
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

    // Inject the mock into a new CommandInformer's run() method
    $injectedCommandInformer = new CommandInformer();
    $injectedCommandInformer->run($commandMock);

    // Call output('test') on the CommandInformer
    expect(
        $injectedCommandInformer->output(str_pad('', pow(10, 3)))
    )->toBeNull();
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\StringValidationException::class);
