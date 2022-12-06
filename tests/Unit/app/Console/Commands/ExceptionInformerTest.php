<?php

/**
 * Unit tests for the ExceptionInformer class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\ExceptionInformer;

/**
 * Testing the warn method
 */

// POSITIVE TEST
test('GIVEN a test exception
    WHEN calling warn()
    THEN output the test message
    ', function () {

    $e = new \Exception('test');
    $command = mock(\Illuminate\Console\Command::class)
        ->shouldReceive('argument')->once()->with('command')->andReturn('test:test')->getMock()
        ->shouldReceive('argument')->once()->with('source')->andReturn('cli')->getMock()
        ->shouldReceive('argument')->once()->with()->andReturn([])->getMock()
        ->shouldReceive('warn')->twice()->with('')->andReturn('')->getMock()
        ->shouldReceive('warn')->once()->with('[💀] Exception')->andReturn('[💀] Exception')->getMock()
        ->shouldReceive('warn')->twice()->with('---------------------------------')->andReturn('---------------------------------')->getMock()
        ->shouldReceive('warn')->once()->with('Message:   test')->andReturn('Message:   test')->getMock()
        ->shouldReceive('warn')->once()->with('Exception: Exception')->andReturn('Exception: Exception')->getMock()
        ->shouldReceive('warn')->once()->with('File:      /home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php')->andReturn('File:      /home/jph/zedtech/finance/zedbot/laravel/tests/Unit/app/Console/Commands/ExceptionInformerTest.php')->getMock()
        ->shouldReceive('warn')->once()->with('Line:      21')->andReturn('Line:      21')->getMock();

    $this->assertNull(
        (new ExceptionInformer())->warn(
            command: $command,
            e: $e
        )
    );
});
