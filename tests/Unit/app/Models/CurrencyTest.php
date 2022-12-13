<?php

/**
 * Unit tests for the Currency class and its methods.
 */

declare(strict_types=1);

use App\Models\Currency;

/**
 * Testing Currencies exist
 */

// POSITIVE TEST
test('GIVEN Currency code = GBP
    WHEN calling "where"
    THEN a Currency is returned
    ', function () {

    $currency = Currency::where('code', 'GBP')->firstOrFail();

    $this->assertInstanceOf(Currency::class, $currency);
})->group('requiresModels');

// NEGATIVE TEST
test('GIVEN Currency code = TEST
    WHEN calling "where"
    THEN throw a ModelNotFoundException
    ', function () {

    $currency = Currency::where('code', 'TEST')->firstOrFail();

    $this->assertInstanceOf(Currency::class, $currency);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
