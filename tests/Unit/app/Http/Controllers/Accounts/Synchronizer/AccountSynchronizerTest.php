<?php

/**
 * Unit tests for the AccountSynchronizer class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\MultiDomain\Requests\Requester;
use App\Http\Controllers\MultiDomain\Requests\AdapterDTO;

test('Even more mock testing...', function () {
    $object = new stdClass();
    $mockRequester = mock('Requester')
        ->shouldReceive("request")
        ->with()
        ->andReturn([])
        ->getMock();

    expect(
        (new AccountSynchronizer())->sync(
            $mockRequester->request()
        )
    )->toBeNull();
});
