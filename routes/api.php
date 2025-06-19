<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TimeFrameController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypesController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/admin/createAdmin',  [AdminController::class, 'store']);
Route::post('/admin/updateAdmin',  [AdminController::class, 'update']);
Route::post('/admin/deleteAdmin',  [AdminController::class, 'destroy']);

Route::post('/admin/createSport',  [SportController::class, 'store']);
Route::post('/admin/updateSport',  [SportController::class, 'update']);
Route::post('/admin/deleteSport',  [SportController::class, 'destroy']);

Route::post('/admin/createPayment',  [PaymentMethodController::class, 'store']);
Route::post('/admin/updatePayment',  [PaymentMethodController::class, 'update']);
Route::post('/admin/deletePayment',  [PaymentMethodController::class, 'destroy']);

Route::post('/admin/createTimeFrame',  [TimeFrameController::class, 'store']);
Route::post('/admin/updateTimeFrame',  [TimeFrameController::class, 'update']);
Route::post('/admin/deleteTimeFrame',  [TimeFrameController::class, 'destroy']);

Route::post('/admin/createCustomer',  [CustomerController::class, 'store']);
Route::post('/admin/updateCustomer',  [CustomerController::class, 'update']);
Route::post('/admin/deleteCustomer',  [CustomerController::class, 'destroy']);

Route::post('/admin/createType',  [TypesController::class, 'store']);
Route::post('/admin/updateType',  [TypesController::class, 'update']);
Route::post('/admin/deleteType',  [TypesController::class, 'destroy']);

Route::post('/admin/createField',  [FieldController::class, 'store']);
Route::post('/admin/updateField',  [FieldController::class, 'update']);
Route::post('/admin/deleteField',  [FieldController::class, 'destroy']);

Route::post('/customer/updateInformation', [CustomerController::class,'updateByUser']);

Route::post('/loadFieldList',  [HomeController::class, 'fieldListAjax']);
Route::post('/getTypeBySport', [FieldController::class, 'getBySport']);

Route::post('/admin/updateBooking',  [BookingController::class, 'updateStatus']);
