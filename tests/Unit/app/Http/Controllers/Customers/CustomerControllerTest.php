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
test('GIVEN env(ZED_TEST_CUSTOMER_IDENTIFIER)
WHEN calling showByIdentifier()
THEN the correct View is returned
', function () {
    // Generate the View
    $view = (new CustomerController())
        ->showByIdentifier(env('ZED_TEST_CUSTOMER_IDENTIFIER'));
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
 * Top-level methods responsible for instantiating and injecting
 * objects are tested via their dependencies as mocking hard
 * dependencies (overloading) has proven problematic...
 */
