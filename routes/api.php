<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', fn(Request $request) => $request->user()->load('wallet'));
    Route::get('/payment-methods', fn() => PaymentMethod::all());
    Route::get('/order/{order}', [OrderController::class, 'get']);

    Route::post('/charge', [OrderController::class, 'store']);
    Route::post('/pay/{order}/using/{paymentMethod}', [OrderController::class, 'pay']);

    Route::get('/orders', [OrderController::class, 'all']);
    Route::get('/transactions', [TransactionController::class, 'all']);
});
