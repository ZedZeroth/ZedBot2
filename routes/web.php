<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Charts\ChartController;
use App\Http\Controllers\Currencies\CurrencyController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Payments\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Welcome page */
Route::get('/', function () {
    return view('welcome');
});

/* Currencies */
Route::get('/currencies', [CurrencyController::class, 'showAll']);
Route::get('/currency/{code}', [CurrencyController::class, 'showByIdentifier']);

/* Payments */
Route::get('/payments', [PaymentController::class, 'showAll']);
Route::get('/payment/networks', [PaymentController::class, 'showNetworks']);
Route::get('/payment/{identfier}', [PaymentController::class, 'showByIdentifier']);
Route::get('/{network}/payments', [PaymentController::class, 'showOnNetwork']);

/* Accounts */
Route::get('/accounts', [AccountController::class, 'showAll']);
Route::get('/account/networks', [AccountController::class, 'showNetworks']);
Route::get('/account/{identfier}', [AccountController::class, 'showByIdentifier']);
Route::get('/{network}/accounts', [AccountController::class, 'showOnNetwork']);

/* Customers */
Route::get('/customers', [CustomerController::class, 'showAll']);
Route::get('/customer/{identfier}', [CustomerController::class, 'showByIdentifier']);

/* Charts */
Route::get('/chart', [ChartController::class, 'view']);
