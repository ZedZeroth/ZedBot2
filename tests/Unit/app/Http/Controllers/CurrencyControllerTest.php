<?php

/**
 * Unit tests for the CurrencyController class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\CurrencyController;
use Illuminate\View\View;
use App\Http\Controllers\Currencies\CurrencyPopulator;

/**
 * Instantiation
 */
test('Expect instantiation of CurrencyController, with no parameters, to return a CurrencyController')
    ->expect(fn() => new CurrencyController())
    ->toBeInstanceOf(CurrencyController::class);

test('Expect instantiation of CurrencyController, with an unnamed parameter, to return a CurrencyController')
    ->expect(fn() => new CurrencyController(
        0
    ))
    ->toBeInstanceOf(CurrencyController::class);

test('Expect a general Error, when instantiating CurrencyController with named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => new CurrencyController(
        xxx: 0
    ))
    ->toBeInstanceOf(CurrencyController::class);

/**
 * The showAll method
 */
test('Expect CurrencyController\'s "showAll" method, with no parameters, to return a View')
    ->expect(fn() => (new CurrencyController())->showAll())
    ->toBeInstanceOf(View::class);

test('Expect CurrencyController\'s "showAll" method, with an unnamed parameter, to return a View')
    ->expect(fn() => (new CurrencyController())->showAll(
        0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when CurrencyController\'s "showAll" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new CurrencyController())->showAll(
        xxx: 0
    ))
    ->toBeInstanceOf(View::class);

/**
 * The showByIdentifier method
 */
test('Expect CurrencyController\'s "showByIdentifier" method, with identifier=\'GBP\', to return a View')
    ->expect(fn() => (new CurrencyController())->showByIdentifier(
        identifier: 'GBP'
    ))
    ->toBeInstanceOf(View::class);

test('Expect an ArgumentCountError, when CurrencyController\'s "showByIdentifier" method has no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('Expect a TypeError, when CurrencyController\'s "showByIdentifier" method has identifier=0 (data type should be string not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier(
        identifier: 0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when CurrencyController\'s "showByIdentifier" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier(
        xxx: 'GBP'
    ))
    ->toBeInstanceOf(View::class);

test('Expect a ModelNotFoundException, when CurrencyController\'s "showByIdentifier" method has identifier=\'XXX\'')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier(
        identifier: 'XXX'
    ))
    ->toBeInstanceOf(View::class);

/**
 * The populate method
 */
test('Expect CurrencyController\'s "populate" method, with no parameters, to return null')
    ->expect(fn() => (new CurrencyController())->populate())
    ->toBeNull();

test('Expect CurrencyController\'s "populate" method, with an unnamed parameter, to return null')
    ->expect(fn() => (new CurrencyController())->populate(
        0
    ))
    ->toBeNull();

test('Expect a general Error, when CurrencyController\'s "populate" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier(
        xxx: 0
    ))
    ->toBeNull();
