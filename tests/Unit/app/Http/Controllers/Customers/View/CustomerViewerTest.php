<?php

/**
 * Unit tests for the CustomerViewer class and its methods.
 */

declare(strict_types=1);

use App\Http\Controllers\Customers\View\CustomerViewer;
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
    $view = (new CustomerViewer())
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
    $view = (new CustomerViewer())
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
THEN throw a ModelNotFoundException
', function () {
    // Generate the View
    $view = (new CustomerViewer())
        ->showByIdentifier('test::test');
    $this->assertInstanceOf(View::class, $view);
})->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
