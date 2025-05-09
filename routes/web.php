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

Route::prefix('admin')
    ->middleware(['auth:web', 'admin'])
    ->group(function () {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/products',[ProductController::class,'index'])->name('products');
        Route::get('/applications',[ApplicationControler::class,'index'])->name('applications');
        Route::controller(UserController::class)->group(function (){
            Route::get('/users','index')->name('users');
            Route::get('/user/detail/{id}','show')->name('users');
        });
    });

