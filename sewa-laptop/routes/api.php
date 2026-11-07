<?php

use App\Http\Controllers\Api\LaptopApiController;
use Illuminate\Support\Facades\Route;

// Endpoint publik
Route::get('/laptops', [LaptopApiController::class, 'index']);
Route::get('/laptops/{id}', [LaptopApiController::class, 'show']);
Route::post('/login', [LaptopApiController::class, 'login']);
