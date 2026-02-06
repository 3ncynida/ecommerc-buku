<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\PaymentCallbackController;

Route::post('/midtrans-callback', [PaymentCallbackController::class, 'receive']);

use App\Http\Controllers\Api\LocationController;

// Endpoint untuk mengambil data wilayah Indonesia
Route::get('/cities/{province_id}', [LocationController::class, 'cities']);
Route::get('/districts/{city_id}', [LocationController::class, 'districts']);
