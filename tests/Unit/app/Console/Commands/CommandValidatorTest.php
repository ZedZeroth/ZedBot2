<?php

/**
 * Unit tests for the CommandValidator class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\CommandValidator;

/**
 * Testing the validate() method
 */

// POSITIVE TEST
test('GIVEN a correctly mocked Command and $commandName
    WHEN calling validate()
    THEN it returns true
    ', function () {

    // Mock a Command
    $commandMock = mock(Illuminate\Console\Command::class)
        ->shouldReceive('argument')->with('source')->andReturn('browser')
        ->shouldReceive('argument')->with()->andReturn(['API' => 'ENM0', 'Number to fetch' => 10])
        ->shouldReceive('argument')->with('API')->andReturn('ENM0')
        ->shouldReceive('argument')->with('Number to fetch')->andReturn(10)
        ->getMock();

    // Inject the mock into a new CommandInformer's run() method
    expect(
        (new CommandValidator())->validate(
            $commandMock,
            'test:test'
        )
    )->toBeTrue();
});

// NEGATIVE TEST
test('GIVEN an invalid source
    WHEN calling validate()
    THEN throw a CommandValidationException
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

// NEGATIVE TEST
test('GIVEN an invalid API
    WHEN calling validate()
    THEN throw a CommandValidationException
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

// NEGATIVE TEST
test('GIVEN an invalid "Number to fetch"
    WHEN calling validate()
    THEN throw a CommandValidationException
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

/**
 * Testing the getEmojiFromCommandSource() method
 */

// POSITIVE TEST
test('GIVEN the source "auto"
    WHEN calling getEmojiFromCommandSource()
    THEN it returns 🤖
    ', function () {

    expect(
        (new CommandValidator())->getEmojiFromCommandSource(
            'auto'
        )
    )->toBe('🤖');
});

// POSITIVE TEST
test('GIVEN the source "test"
    WHEN calling getEmojiFromCommandSource()
    THEN it returns 🤖
    ', function () {

    expect(
        (new CommandValidator())->getEmojiFromCommandSource(
            'test'
        )
    )->toBeNull();
});

// NEGATIVE TEST
test('GIVEN the source ""
    WHEN calling getEmojiFromCommandSource()
    THEN throw a StringValidationException
    ', function () {

    expect(
        (new CommandValidator())->getEmojiFromCommandSource(
            ''
        )
    )->toBe('🤖');
})->expectException(\App\Http\Controllers\MultiDomain\Validators\StringValidationException::class);
