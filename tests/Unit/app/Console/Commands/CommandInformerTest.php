<?php

/**
 * Unit tests for the CommandInformer class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\CommandInformer;

/**
 * Testing the run() method
 */

// POSITIVE TEST
test('GIVEN a correctly mocked Command
    WHEN calling run()
    THEN it returns null
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(3)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->twice()->with('source')->andReturn('cli')
        ->shouldReceive('info')->once()->with('[📟] test:test')->andReturn()
        ->shouldReceive('info')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('argument')->twice()->with()->andReturn([])
        ->shouldReceive('info')->once()->with('... Running "test:test"')->andReturn()
        ->shouldReceive('runThisCommand')->once()->with()->andReturn()
        ->shouldReceive('info')->once()->with('... 0ms DONE')->andReturn()
        ->shouldReceive('info')->once()->with('No new models created.')->andReturn()
        ->shouldReceive('info')->once()->with('')->andReturn()
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
});

// NEGATIVE TEST
test('GIVEN a mocked Command with "command" argument "t:t"
    WHEN calling run()
    THEN throw a StringValidationException
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->once()->with('command')->andReturn('t:t')
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandInformer())->run($commandMock)
    )->toBeNull();
})
->expectException(\App\Http\Controllers\MultiDomain\Validators\StringValidationException::class);

// NEGATIVE TEST
test('GIVEN a mocked Command with "source" argument "test"
    WHEN calling run()
    THEN throw a CommandValidationException
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->once()->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->twice()->with('source')->andReturn('test')
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

// POSITIVE TEST
test('GIVEN a correctly mocked Command
    WHEN calling ouput("[📟] test:test")
    THEN it returns null
    ', function () {
    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(3)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->twice()->with('source')->andReturn('cli')
        ->shouldReceive('info')->twice()->with('[📟] test:test')->andReturn()
        ->shouldReceive('info')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('argument')->twice()->with()->andReturn([])
        ->shouldReceive('info')->once()->with('... Running "test:test"')->andReturn()
        ->shouldReceive('runThisCommand')->once()->with()->andReturn()
        ->shouldReceive('info')->once()->with('... 0ms DONE')->andReturn()
        ->shouldReceive('info')->once()->with('No new models created.')->andReturn()
        ->shouldReceive('info')->once()->with('')->andReturn()
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    $injectedCommandInformer = new CommandInformer();
    $injectedCommandInformer->run($commandMock);

    // Call output('test') on the CommandInformer
    expect(
        $injectedCommandInformer->output('[📟] test:test')
    )->toBeNull();
});

// NEGATIVE TEST
test('GIVEN a string of length 1000
    WHEN calling ouput()
    THEN throw a StringValidationException
    ', function () {
    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(3)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->twice()->with('source')->andReturn('cli')
        ->shouldReceive('info')->once()->with('[📟] test:test')->andReturn()
        ->shouldReceive('info')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('argument')->twice()->with()->andReturn([])
        ->shouldReceive('info')->once()->with('... Running "test:test"')->andReturn()
        ->shouldReceive('runThisCommand')->once()->with()->andReturn()
        ->shouldReceive('info')->once()->with('... 0ms DONE')->andReturn()
        ->shouldReceive('info')->once()->with('No new models created.')->andReturn()
        ->shouldReceive('info')->once()->with('')->andReturn()
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
