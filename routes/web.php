<?php

use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TimeFrameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingDetailController;


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
Route::get('/auth/login', [CustomerController::class, 'viewLogin'])->name('login');
Route::get('/auth/register', [CustomerController::class, 'viewRegister'])->name('auth.register');
Route::get('/logout', [HomeController::class, 'logout']);


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'viewLogin']);
    Route::get('/logout', [AdminController::class, 'viewLogin']);
});

Route::middleware('auth:web')->group(function(){
    Route::get('/user/profile', [CustomerController::class, 'viewProfile'])->name('user.profile');
    Route::get('/user/password', [CustomerController::class, 'viewChangePass'])->name('user.password');
    Route::get('/field/sport/{id}', [HomeController::class, 'fieldList'])->name('user.field');
    Route::get('/cart', [HomeController::class, 'cart'])->name('user.cart');
     Route::get('/user/history', [CustomerController::class, 'history'])->name('user.history');
    Route::post('/api/checkout', [HomeController::class, 'checkout']);
});

// Bọc Middleware bắt phải là admin
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/adminstrator/manage', [AdminController::class, 'createAdmin'])->name('admin.adminstrator');
    Route::get('/sport/manage', [SportController::class, 'index'])->name('admin.sport');
    Route::get('/paymentmethod/manage', [PaymentMethodController::class, 'index'])->name('admin.paymentmethod');
    Route::get('/timeframe/manage', [TimeFrameController::class, 'index'])->name('admin.timeframe');
    Route::get('/customer/manage', [CustomerController::class, 'create'])->name('admin.customer');
    Route::get('/type/manage', [TypesController::class, 'index'])->name('admin.type');
    Route::get('/field/manage', [FieldController::class, 'index'])->name('admin.field');
    Route::get('/booking/manage', [BookingController::class, 'index'])->name('admin.booking');
    Route::get('/detail/{id}', [BookingDetailController::class, 'index']);
    Route::get('/bookings/create', [BookingController::class, 'createView'])->name('admin.bookings.create');
    Route::get('/bookings/today', [BookingController::class, 'todaySchedule'])->name('admin.bookings.today');
});


//Auth

Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::post('/customer/logout', [CustomerAuthController::class, 'logout']);
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::get('/vnpay/callback', [PaymentMethodController::class, 'vnpayReturn']);