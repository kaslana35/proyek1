<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Endpoint Publik
Route::middleware('throttle:api')->group(function () {
    Route::get('/laptops', [ProductController::class, 'index']);
    Route::get('/laptops/{id}', [ProductController::class, 'show']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Endpoint Terproteksi (Sanctum)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/laptops', [ProductController::class, 'store']);
    Route::put('/laptops/{id}', [ProductController::class, 'update']);
    Route::delete('/laptops/{id}', [ProductController::class, 'destroy']);
    Route::post('/laptops/{id}/sewa', [TransactionController::class, 'store']);
});
