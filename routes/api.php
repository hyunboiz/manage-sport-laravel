<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SportController;



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


