<?php

/**
 * Unit tests for the CurrencyViewer class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Currencies\CurrencyViewer;
use Illuminate\View\View;

/**
 * Instantiation
 */
test('Expect instantiation of CurrencyViewer, with no parameters, to return a CurrencyViewer')
    ->expect(fn() => new CurrencyViewer())
    ->toBeInstanceOf(CurrencyViewer::class);

test('Expect instantiation of CurrencyViewer, with an unnamed parameter, to return a CurrencyViewer')
    ->expect(fn() => new CurrencyViewer(
        0
    ))
->toBeInstanceOf(CurrencyViewer::class);

test('Expect a general Error, when instantiating CurrencyViewer with named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => new CurrencyViewer(
        xxx: 0
    ))
    ->toBeInstanceOf(CurrencyViewer::class);

/**
 * The showAll method
 */
test('Expect CurrencyViewer\'s "showAll" method, with no parameters, to return a View')
    ->expect(fn() => (new CurrencyViewer())->showAll())
    ->toBeInstanceOf(View::class);

test('Expect CurrencyViewer\'s "showAll" method, with an unnamed parameter, to return a View')
    ->expect(fn() => (new CurrencyViewer())->showAll(
        0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when CurrencyViewer\'s "showAll" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new CurrencyViewer())->showAll(
        xxx: 0
    ))
    ->toBeInstanceOf(View::class);

/**
 * The showByIdentifier method
 */
test('Expect CurrencyViewer\'s "showByIdentifier" method, with identifier=\'GBP\', to return a View')
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier(
        identifier: 'GBP'
    ))
    ->toBeInstanceOf(View::class);

test('Expect an ArgumentCountError, when CurrencyViewer\'s "showByIdentifier" method has no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('Expect a TypeError, when CurrencyViewer\'s "showByIdentifier" method has identifier=0 (data type should be string not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier(
        identifier: 0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when CurrencyViewer\'s "showByIdentifier" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier(
        xxx: 'GBP'
    ))
    ->toBeInstanceOf(View::class);

test('Expect a ModelNotFoundException, when CurrencyViewer\'s "showByIdentifier" method has identifier=\'XXX\'')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier(
        identifier: 'XXX'
    ))
    ->toBeInstanceOf(View::class);
