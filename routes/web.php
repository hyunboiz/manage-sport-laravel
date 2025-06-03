<?php

use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Group for user
Route::get('/', [HomeController::class, 'index']);




Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::get('/login', [AdminController::class, 'viewLogin']);
    Route::get('/adminstrator/manage', [AdminController::class, 'createAdmin']);
    Route::get('/sport/manage', [SportController::class, 'index']);
    Route::get('/paymentmethod/manage', [PaymentMethodController::class, 'index']);
});
Route::get('/admin', [AdminController::class, 'index']);
