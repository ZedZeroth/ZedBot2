<?php

/**
 * Unit tests for the AccountController class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use Illuminate\View\View;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Console\Commands\SyncCommandDTO;

/**
 * Instantiation
 */
test('GIVEN no parameters WHEN instantiating THEN an instance is returned')
    ->expect(fn() => new AccountController())
    ->toBeInstanceOf(AccountController::class);

test('Expect instantiation of AccountController, with an unnamed parameter, to return an AccountController')
    ->expect(fn() => new AccountController(
        0
    ))
    ->toBeInstanceOf(AccountController::class);

test('Expect a general Error, when instantiating AccountController with named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => new AccountController(
        xxx: 0
    ))
    ->toBeInstanceOf(AccountController::class);

/**
 * The showAll method
 */
test('Expect AccountController\'s "showAll" method, with no parameters, to return a View')
    ->expect(fn() => (new AccountController())->showAll())
    ->toBeInstanceOf(View::class);

test('Mock testing...', function () {
    $mock = mock(AccountViewer::class)->expect(
        showAll: fn () => true
    );

    expect((new AccountController())->showAll())->toBeInstanceOf(View::class);
    expect($mock->showAll())->toBeTrue();
});

test('Expect AccountController\'s "showAll" method, with an unnamed parameter, to return a View')
    ->expect(fn() => (new AccountController())->showAll(
        0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when AccountController\'s "showAll" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->showAll(
        xxx: 0
    ))
    ->toBeInstanceOf(View::class);

/**
 * The showByIdentifier method
 */
test('Expect AccountController\'s "showByIdentifier" method, with identifier=env(\'ZED_TEST_ACCOUNT_IDENTIFIER\'), to return a View')
    ->expect(fn() => (new AccountController())->showByIdentifier(
        identifier: env('ZED_TEST_ACCOUNT_IDENTIFIER')
    ))
    ->toBeInstanceOf(View::class);

test('Expect an ArgumentCountError, when AccountController\'s "showByIdentifier" method has no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('Expect a TypeError, when AccountController\'s "showByIdentifier" method has identifier=0 (data type should be string not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->showByIdentifier(
        identifier: 0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when AccountController\'s "showByIdentifier" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->showByIdentifier(
        xxx: 'GBP'
    ))
    ->toBeInstanceOf(View::class);

test('Expect a ModelNotFoundException, when AccountController\'s "showByIdentifier" method has identifier=\'XXX\'')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new AccountController())->showByIdentifier(
        identifier: 'XXX'
    ))
    ->toBeInstanceOf(View::class);

/**
 * The showNetworks method
 */
test('Expect AccountController\'s "showNetworks" method, with no parameters, to return a View')
    ->expect(fn() => (new AccountController())->showNetworks())
    ->toBeInstanceOf(View::class);

test('Expect AccountController\'s "showNetworks" method, with an unnamed parameter, to return a View')
    ->expect(fn() => (new AccountController())->showNetworks(
        0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when AccountController\'s "showNetworks" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->showNetworks(
        xxx: 0
    ))
    ->toBeInstanceOf(View::class);

/**
 * The showOnNetwork method
 */
test('Expect AccountController\'s "showOnNetwork" method, with identifier=\'FPS\', to return a View')
    ->expect(fn() => (new AccountController())->showOnNetwork(
        network: 'FPS'
    ))
    ->toBeInstanceOf(View::class);

test('Expect an ArgumentCountError, when AccountController\'s "showOnNetwork" method has no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->showOnNetwork())
    ->toBeInstanceOf(View::class);

test('Expect a TypeError, when AccountController\'s "showOnNetwork" method has network=0 (data type should be string not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->showOnNetwork(
        network: 0
    ))
    ->toBeInstanceOf(View::class);

test('Expect a general Error, when AccountController\'s "showOnNetwork" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->showOnNetwork(
        xxx: 'FPS'
    ))
    ->toBeInstanceOf(View::class);

test('Expect a ModelNotFoundException, when AccountController\'s "showOnNetwork" method has identifier=\'XXX\'')
    ->expectException(Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new AccountController())->showByIdentifier(
        identifier: 'XXX'
    ))
    ->toBeInstanceOf(View::class);

/**
 * The sync method
 */
test('Expect AccountController\'s "sync" method, injected with a valid SyncCommandDTO, to return null')
    ->expect(fn() => (new AccountController())->sync(
        syncCommandDTO: new SyncCommandDTO(
            provider: 'ENM',
            numberToFetch: 1
        )
    ))
    ->toBeNull();

test('GIVEN xxx WHEN xxx THEN xxx', function () {
    $mock = mock(AccountController::class)->expect(
        sync: fn ($test) => false
    );

    expect($mock->sync(new SyncCommandDTO('', 0)))->toBeNull();
});

test('Expect an ArgumentCountError, when AccountController\'s "sync" method has no parameters')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->sync())
    ->toBeNull();

test('Expect a TypeError, when AccountController\'s "sync" method has syncCommandDTO=0 (data type should be SyncCommandDTO not int)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->sync(
        syncCommandDTO: 0
    ))
    ->toBeNull();

test('Expect a general Error, when AccountController\'s "sync" method is injected with a syncCommandDTO with provider=\'XXX\'')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->sync(
        syncCommandDTO: new SyncCommandDTO(
            provider: 'XXX',
            numberToFetch: 0
        )
    ))
    ->toBeNull();

test('Expect a TypeError, when AccountController\'s "sync" method is injected with a syncCommandDTO with numberToFetch=\'0\' (data type should be int not string)')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->sync(
        syncCommandDTO: new SyncCommandDTO(
            provider: 'ENM',
            numberToFetch:'0'
        )
    ))
    ->toBeNull();

test('Expect a general Error, when AccountController\'s "sync" method has named parameter "xxx"')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->sync(
        xxx: new SyncCommandDTO(
            provider: 'ENM',
            numberToFetch: 0
        )
    ))
    ->toBeNull();
