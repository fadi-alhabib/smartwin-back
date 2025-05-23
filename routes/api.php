<?php

use App\Http\Controllers\Api\Gateway\MtnPaymentController;
use App\Http\Controllers\Api\Store\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('/products', [ProductController::class, 'index']);
// routes/api.php


Route::prefix('mtn-payments')->group(function () {
    Route::post('/activate', [MtnPaymentController::class, 'activateTerminal']);
    Route::post('/create-invoice', [MtnPaymentController::class, 'createInvoice']);
    Route::post('/initiate',        [MtnPaymentController::class, 'initiatePayment']);
    Route::post('/confirm',         [MtnPaymentController::class, 'confirmPayment'])->middleware('auth:sanctum');
    Route::post('/refund/initiate', [MtnPaymentController::class, 'refundInitiate']);
    Route::post('/refund/confirm',  [MtnPaymentController::class, 'refundConfirm']);
    Route::post('/refund/cancel',   [MtnPaymentController::class, 'refundCancel']);
});
