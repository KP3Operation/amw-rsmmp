<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Shared\MeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'sendOtp']);
    Route::post('/verification', [LoginController::class, 'authenticate']);
    Route::post('/register/patient', [RegisterController::class, 'storePatient']);
    Route::post('/register/doctor', [RegisterController::class, 'storeDoctor']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/me', [MeController::class, 'index']);
        Route::put('/register/patient/{phoneNumber}', [RegisterController::class, 'updatePatient']);
    });
});
