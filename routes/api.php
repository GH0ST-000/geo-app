<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MunicipalityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/me', [AuthController::class, 'me']);
});

// Public Municipality routes
Route::get('/municipalities', [MunicipalityController::class, 'index']);
Route::get('/municipalities/{id}', [MunicipalityController::class, 'show']);
