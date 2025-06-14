<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MunicipalityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\StandardController;
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
    Route::post('/account/deactivate', [UserController::class, 'deactivateAccount']);

    // Profile routes
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    // Standards routes
    Route::post('/standards', [StandardController::class, 'store']);
    Route::get('/standards', [StandardController::class, 'index']);
    Route::get('/standards/group/{groupId}', [StandardController::class, 'getGroup']);
    Route::get('/standards/{slug}', [StandardController::class, 'index']);
    Route::delete('/standards/{id}', [StandardController::class, 'destroy']);
    
    // Product routes (protected)
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::post('products/{product}', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::delete('products/{productId}/images/{imageId}', [ProductController::class, 'deleteImage']);
    Route::delete('products/{productId}/files/{fileId}', [ProductController::class, 'deleteStandardFile']);
    Route::post('products/{product}/files', [ProductController::class, 'uploadStandardFiles']);
});

// Public Municipality routes
Route::get('/municipalities', [MunicipalityController::class, 'index']);
Route::get('/municipalities/{id}', [MunicipalityController::class, 'show']);

// Public User routes
Route::get('/users/verified', [UserController::class, 'getVerifiedUsers']);
Route::get('/users/{ulid}', [UserController::class, 'getUserByUlid']);

// Public Product routes
Route::get('/public/products', [ProductController::class, 'getAllProducts']);
Route::get('/public/products/{id}', [ProductController::class, 'getProduct']);
Route::get('/public/users/{ulid}/products', [ProductController::class, 'getUserProducts']);

// Admin routes - accessible within the application
Route::get('/products/{id}', [ProductController::class, 'getProduct']);

// Public files display route (doesn't require authentication)
Route::get('/public/product-files/{id}', [ProductController::class, 'getProductFilesForDisplay']);

// Password generation route
Route::post('/generate-password', [PasswordController::class, 'generateAndSendPassword']);
