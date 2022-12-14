<?php

/**
 * Unit tests for the ExceptionInformer class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\ExceptionInformer;

/**
 * Testing the warn() method
 */

// POSITIVE TEST
test('GIVEN a test exception & error
    WHEN calling warn()
    THEN return null
    ', function () {

    $exceptionMock = new \Exception('test');
    $errorMock = new \Error('test');
    $commandMock = mock(\Illuminate\Console\Command::class)
        ->shouldReceive('argument')->twice()->with('command')->andReturn('test:test')
        ->shouldReceive('argument')->twice()->with('source')->andReturn('cli')
        ->shouldReceive('argument')->twice()->with()->andReturn([])
        ->shouldReceive('warn')->times(4)->with('')->andReturn('')
        ->shouldReceive('warn')->once()->with('[💀] Exception')->andReturn('[💀] Exception')
        ->shouldReceive('warn')->once()->with('[💀] Error')->andReturn('[💀] Error')
        ->shouldReceive('warn')->times(4)->with('---------------------------------')->andReturn('---------------------------------')
        ->shouldReceive('warn')->twice()->with('test')->andReturn()
        ->shouldReceive('warn')->once()->with('/home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php (21)')->andReturn()
        ->shouldReceive('warn')->once()->with('/home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php (22)')->andReturn()
        ->getMock();
        /*
        ->shouldReceive('warn')->twice()->with('Message:   test')->andReturn('Message:   test')->getMock()
        ->shouldReceive('warn')->once()->with('Exception: Exception')->andReturn('Exception: Exception')->getMock()
        ->shouldReceive('warn')->once()->with('Exception: Error')->andReturn('Exception: Error')->getMock()
        ->shouldReceive('warn')->twice()->with('File:      /home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php')->andReturn('File:      /home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php')->getMock()
        ->shouldReceive('warn')->once()->with('Line:      21')->andReturn('Line:      21')->getMock()
        ->shouldReceive('warn')->once()->with('Line:      22')->andReturn('Line:      22')->getMock();
        */

    $this->assertNull(
        (new ExceptionInformer())->warn(
            command: $commandMock,
            e: $exceptionMock
        )
    );

    $this->assertNull(
        (new ExceptionInformer())->warn(
            command: $commandMock,
            e: $errorMock
        )
    );
});
