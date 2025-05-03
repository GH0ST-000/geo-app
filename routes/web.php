<?php

use App\Http\Controllers\ApplicationControler;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[WelcomeController::class,'index'])->name('home');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Route::get('/dashboard', [DashboardController::class, 'index'])
//    ->middleware('auth:web')
//    ->name('dashboard');

Route::prefix('admin')
    ->middleware(['auth:web', 'admin'])
    ->group(function () {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/users',[UserController::class,'index'])->name('users');
        Route::get('/products',[ProductController::class,'index'])->name('products');
        Route::get('/applications',[ApplicationControler::class,'index'])->name('applications');
    });

