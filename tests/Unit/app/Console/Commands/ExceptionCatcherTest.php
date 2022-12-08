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
    THEN return null
    ', function () {

    $exceptionType = \Exception::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(5)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->times(5)->with('source')->andReturn('cli')
        ->shouldReceive('argument')->times(4)->with()->andReturn([])
        ->shouldReceive('runThisCommand')->once()->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->twice()->with('')->andReturn()
        ->shouldReceive('warn')->once()->with('[💀] Exception')->andReturn()
        ->shouldReceive('warn')->once()->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->once()->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->once()->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->once()->with('Line:      28')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->times(3)->andReturn()
        ->getMock();

    $this->assertNull(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    );
});

// POSITIVE TEST
test('GIVEN a Command mocked to throw a StringValidationException
    WHEN calling catch()
    THEN return null
    ', function () {

    $exceptionType = \App\Http\Controllers\MultiDomain\Validators\StringValidationException::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(5)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->times(5)->with('source')->andReturn('cli')
        ->shouldReceive('argument')->times(4)->with()->andReturn([])
        ->shouldReceive('runThisCommand')->once()->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->twice()->with('')->andReturn()
        ->shouldReceive('warn')->once()->with('[💀] StringValidationException')->andReturn()
        ->shouldReceive('warn')->once()->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->once()->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->once()->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->once()->with('Line:      59')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->times(3)->andReturn()
        ->getMock();

    $this->assertNull(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    );
});

// POSITIVE TEST
test('GIVEN a Command mocked to throw an unknown exception
    WHEN calling catch()
    THEN return null
    ', function () {

    $exceptionType = \App\Http\Controllers\MultiDomain\Validators\TestException::class;

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->times(5)->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->times(5)->with('source')->andReturn('cli')
        ->shouldReceive('argument')->times(4)->with()->andReturn([])
        ->shouldReceive('runThisCommand')->once()->with()->andThrow(new $exceptionType('test'))
        ->shouldReceive('warn')->twice()->with('')->andReturn()
        ->shouldReceive('warn')->once()->with('[💀] TestException')->andReturn()
        ->shouldReceive('warn')->once()->with('Message:   test')->andReturn()
        ->shouldReceive('warn')->once()->with('Exception: ' . $exceptionType)->andReturn()
        ->shouldReceive('warn')->once()->with('File:      ' . __FILE__)->andReturn()
        ->shouldReceive('warn')->once()->with('Line:      90')->andReturn() // Line exception thrown above
        ->shouldReceive('warn')->twice()->with('---------------------------------')->andReturn()
        ->shouldReceive('info')->times(3)->andReturn()
        ->getMock();

    $this->assertNull(
        (new ExceptionCatcher())->catch(
            $commandMock
        )
    );
});
