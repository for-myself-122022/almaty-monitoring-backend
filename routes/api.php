<?php

use App\Http\Controllers\DashboardAPI\DriverController;
use App\Http\Controllers\DashboardAPI\ObjectController;
use App\Http\Controllers\MobileAPI\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('mobile')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::resource('objects', \App\Http\Controllers\MobileAPI\ObjectController::class);
        Route::post('/objects/accept/{id}', [\App\Http\Controllers\MobileAPI\ObjectController::class, 'accept']);
    });
});

Route::prefix('dashboard')->group(function () {
    Route::resource('objects', ObjectController::class);
    Route::get('/radius', [ObjectController::class, 'radius']);

    Route::resource('drivers', DriverController::class);
});
