<?php

/**
 * Overload tests for the CustomerController class and its methods.
 */

declare(strict_types=1);

namespace Tests\Unit\App\Http\Controllers\Customers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\Customers\CustomerController;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */

class CustomerControllerZOverloadTest extends TestCase
{
    public function testImport(): void
    {
/**
 * Testing import() method
 */

// NOTE: Overload test must be run last
/*
// POSITIVE TEST
test('GIVEN an overriden CustomerImporter
WHEN calling import()
THEN return true
', function () {
*/
        // Overload the CustomerImporter to return true on import()
        $customerImporterMock = mock(
            'overload:'
            . \App\Http\Controllers\Customers\Import\CustomerImporter::class
        )   ->shouldReceive('import')
            ->with()
            ->andReturn(true)
            ->getMock();

        // Expect the CustomerController to call the CustomerImporter's
        // import() method and return its output.
        //expect(
        //    (new CustomerController())->import()
        //)->toBeTrue();

        $this->assertSame(true, (new CustomerController())->import());
    }
}
