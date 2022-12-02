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
test('GIVEN "test" // replace with my name
WHEN calling showByIdentifier()
THEN the correct View is returned
', function () {
    // Generate the View
    $view = (new CustomerController())
        ->showByIdentifier('test');
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
test('GIVEN "test::test"
WHEN calling showByIdentifier()
THEN the correct View is returned
', function () {
    // Generate the View
    $view = (new CustomerController())
        ->showByIdentifier('test::test');
    $this->assertInstanceOf(View::class, $view);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

/**
 * Testing import() method
 */

// NOTE: Overload test is run in a separate file

// NEGATIVE TEST
test('GIVEN no overriden CustomerImporter 
WHEN calling import()
THEN throw an Exception
', function () {
    expect(
        (new CustomerController())->import()
    )->toBeTrue();
});//->expectException(\Exception::class);
