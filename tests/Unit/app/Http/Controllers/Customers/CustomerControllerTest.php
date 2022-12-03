<?php

/**
 * Unit tests for the CustomerController class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\CustomerController;
use Illuminate\View\View;

/**
 * Testing showAll() method
 */

 // POSITIVE TEST
test('GIVEN no parameters
    WHEN calling showAll()
    THEN the correct View is returned
    ', function () {

    // Generate the View
    $view = (new CustomerController())
        ->showAll();
    $this->assertInstanceOf(View::class, $view);

    // Check the "view" property is "customers"
    $viewProperty = (new \ReflectionClass($view))->getProperty('view');
    $viewProperty->setAccessible(true);
    $viewProperty->getValue($view);
    $this->assertSame(
        'customers',
        $viewProperty->getValue($view)
    );

    // Check the "data" property is the correct array
    $dataProperty = (new \ReflectionClass($view))->getProperty('data');
    $dataProperty->setAccessible(true);
    $dataProperty->getValue($view);
    $this->assertSame(
        ['customers'],
        array_keys($dataProperty->getValue($view))
    );
});

/**
 * Testing showByIdentifier() method
 */

// POSITIVE TEST
test('GIVEN "test::test" // replace with my name
WHEN calling showByIdentifier()
THEN the correct View is returned
', function () {
    // Generate the View
    $view = (new CustomerController())
        ->showByIdentifier('test::test');
    $this->assertInstanceOf(View::class, $view);

    // Check the "view" property is "customer"
    $viewProperty = (new \ReflectionClass($view))->getProperty('view');
    $viewProperty->setAccessible(true);
    $viewProperty->getValue($view);
    $this->assertSame(
        'customer',
        $viewProperty->getValue($view)
    );

    // Check the "data" property has the correct array keys
    $dataProperty = (new \ReflectionClass($view))->getProperty('data');
    $dataProperty->setAccessible(true);
    $dataProperty->getValue($view);
    $this->assertSame(
        ['customer', 'modelTable'],
        array_keys($dataProperty->getValue($view))
    );
});

// NEGATIVE TEST
test('GIVEN "test::test::test"
WHEN calling showByIdentifier()
THEN the correct View is returned
', function () {
    // Generate the View
    $view = (new CustomerController())
        ->showByIdentifier('test::test::test');
    $this->assertInstanceOf(View::class, $view);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing import() method
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
/*
// POSITIVE TEST
test('GIVEN an overriden CustomerImporter
WHEN calling import()
THEN return true
', function () {

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
    expect(
        (new CustomerController())->import()
    )->toBeTrue();
});
*/
// NEGATIVE TEST
test('GIVEN no overriden CustomerImporter 
WHEN calling import()
THEN throw an Error
', function () {
    expect(
        (new CustomerController())->import()
    )->toBeTrue();
})->expectException(\Error::class);
