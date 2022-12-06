<?php

/**
 * Unit tests for the CommandValidationException class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\CommandValidationException;

/**
 * Testing the getMessage method
 */

// POSITIVE TEST
test('GIVEN a message
    WHEN calling getMessage()
    THEN return the message
    ', function () {

    $this->assertSame(
        'test',
        (new CommandValidationException('test'))->getMessage()
    );
});

// NEUTRAL TEST
test('GIVEN no message
    WHEN calling getMessage()
    THEN return ""
    ', function () {

    $this->assertSame(
        '',
        (new CommandValidationException())->getMessage()
    );
});
