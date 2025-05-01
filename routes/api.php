<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MunicipalityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PasswordController;
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
    
    // Product routes (protected)
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::post('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::delete('products/{productId}/images/{imageId}', [ProductController::class, 'deleteImage']);
});

// Public Municipality routes
Route::get('/municipalities', [MunicipalityController::class, 'index']);
Route::get('/municipalities/{id}', [MunicipalityController::class, 'show']);

// Public User routes
Route::get('/users/verified', [UserController::class, 'getVerifiedUsers']);

// Public Product routes
Route::get('/public/products', [ProductController::class, 'getAllProducts']);
Route::get('/public/products/{id}', [ProductController::class, 'getProduct']);

// Password generation route
Route::post('/generate-password', [PasswordController::class, 'generateAndSendPassword']);
