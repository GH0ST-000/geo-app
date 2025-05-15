<?php

use App\Http\Controllers\ApplicationControler;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
Route::fallback(function () {
    return response()->view('pages.errors.404', [], 404);
});
Route::get('/',[WelcomeController::class,'index'])->name('home');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')
    ->middleware(['auth:web', 'admin'])
    ->group(function () {
        Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::controller(UserController::class)->group(function (){
            Route::get('/users','index')->name('users');
            Route::post('/users/','delete')->name('delete-users');
            Route::get('/user/detail/{id}','show')->name('users');
        });
        Route::controller(ProductController::class)->group(function (){
            Route::get('/products','index')->name('products');
            Route::get('/product/detail/{id}','show')->name('products-detail');
        });
        Route::controller(ApplicationControler::class)->group(function (){
            Route::get('/applications','index')->name('applications');
            Route::get('/applications/detail/{id}','show')->name('applications-detail');
            Route::post('/applications/active-user/','update')->name('applications-active');
            Route::post('/applications/reject-user/','reject')->name('applications-reject');
        });
    });

