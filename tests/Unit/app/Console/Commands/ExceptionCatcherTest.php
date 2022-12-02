<?php

/**
 * Unit tests for the ExceptionCatcher class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\ExceptionCatcher;

/**
 * Testing the catch() method
 */

// POSITIVE TEST
test('GIVEN a Command mocked to throw an Exception
    WHEN calling catch()
    THEN it returns null
    ', function () {

    $exceptionType = \Exception::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('cli')
        ->shouldReceive('argument')->with()->andReturn([])
        ->shouldReceive('runThisCommand')->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->with('')->andReturn()
        ->shouldReceive('warn')->with('[💀] Exception')->andReturn()
        ->shouldReceive('warn')->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->with('Line:      28')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->andReturn()
        ->getMock();

    expect(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    )->toBeNull();
});

// POSITIVE TEST
test('GIVEN a Command mocked to throw a StringValidationException
    WHEN calling catch()
    THEN it returns null
    ', function () {

    $exceptionType = \App\Http\Controllers\MultiDomain\Validators\StringValidationException::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('cli')
        ->shouldReceive('argument')->with()->andReturn([])
        ->shouldReceive('runThisCommand')->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->with('')->andReturn()
        ->shouldReceive('warn')->with('[💀] StringValidationException')->andReturn()
        ->shouldReceive('warn')->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->with('Line:      59')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->andReturn()
        ->getMock();

    expect(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    )->toBeNull();
});

// POSITIVE TEST
test('GIVEN a Command mocked to throw an unknown exception
    WHEN calling catch()
    THEN it returns null
    ', function () {

    $exceptionType = \App\Http\Controllers\MultiDomain\Validators\TestException::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->with('source')->andReturn('cli')
        ->shouldReceive('argument')->with()->andReturn([])
        ->shouldReceive('runThisCommand')->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->with('')->andReturn()
        ->shouldReceive('warn')->with('[💀] TestException')->andReturn()
        ->shouldReceive('warn')->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->with('Line:      90')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->andReturn()
        ->getMock();

    expect(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    )->toBeNull();
});
