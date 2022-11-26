<?php

/**
 * Unit tests for the SyncCommandDTO class and its methods.
 */

declare(strict_types=1);

use App\Console\Commands\SyncCommandDTO;

/**
 * Instantiation
 */
test('Expect instantiation of SyncCommandDTO, with provider=\'ENM\' and numberToFetch=0, to return a SyncCommandDTO')
    ->expect(fn() => new SyncCommandDTO(
        provider: 'ENM',
        numberToFetch: 0
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('Expect an ArgumentCountError, when instantiating SyncCommandDTO with no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => new SyncCommandDTO())
    ->toBeInstanceOf(SyncCommandDTO::class);

test('Expect an ArgumentCountError, when instantiating SyncCommandDTO without a "numberToFetch" parameter')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(
        provider: 'ENM'
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('Expect a TypeError, when instantiating SyncCommandDTO with provider=0 (data type should be string not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(
        provider: 0,
        numberToFetch: 0
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('Expect a TypeError, when instantiating SyncCommandDTO with numberToFetch=\'0\' (data type should be int not string)')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(
        provider: 'ENM',
        numberToFetch: '0'
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('Expect a general Error, when instantating SyncCommandDTO with named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => new SyncCommandDTO(
        xxx: 'ENM',
        numberToFetch: 0
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);
