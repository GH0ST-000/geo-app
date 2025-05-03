<?php

use App\Http\Controllers\ApplicationControler;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/',[WelcomeController::class,'index'])->name('home');

Route::prefix('admin' )->group(function (){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/users',[UserController::class,'index'])->name('users');
    Route::get('/products',[ProductController::class,'index'])->name('products');
    Route::get('/applications',[ApplicationControler::class,'index'])->name('applications');

});

