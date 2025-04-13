<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MunicipalityController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/auth/password/update', [AuthController::class, 'updatePassword']);

    // Profile routes
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    // Admin routes
    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
        Route::post('/users/{userId}/verify', [\App\Http\Controllers\Api\AdminController::class, 'updateUserVerification']);
    });
});

// Public Municipality routes
Route::get('/municipalities', [MunicipalityController::class, 'index']);
Route::get('/municipalities/{id}', [MunicipalityController::class, 'show']);
