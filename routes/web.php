<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('/auth/login');
});

Route::get('/auth/{vuejs?}', function () {
    return view('layouts.auth');
})->where('vuejs', '[\/\w\.-]*');

Route::get('/patient/{vuejs?}', function () {
    return view('layouts.patient');
})->where('vuejs', '[\/\w\.-]*');

Route::get('/doctor/{vuejs?}', function () {
    return view('layouts.doctor');
})->where('vuejs', '[\/\w\.-]*');
