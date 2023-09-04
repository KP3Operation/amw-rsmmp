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

// FIXME: Need to remove
// Route::get('/testapi', function () {
//     $response = \Illuminate\Support\Facades\Http::withHeaders([
//             'API-Key' => config('taptalk.api_key'),
//             'Content-Type' => 'application/json'
//         ])->post(config('taptalk.send_message_api'), [
//             'phone' => '+6282211223344',
//             'messageType' => config('taptalk.message_type'),
//             'body' => '123321'
//     ]);

//     if ($response->ok()) {
//         $response->collect('status')->first();
//         $response->collect('data')['id'];
//     }
// });

Route::get('/auth/{vuejs?}', function () {
    return view('layouts.auth');
})->where('vuejs', '[\/\w\.-]*');
