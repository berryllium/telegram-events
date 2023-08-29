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

Route::resource('/form', \App\Http\Controllers\FormController::class);
Route::resource('/channel', \App\Http\Controllers\TelegramChannelController::class);
Route::get('/', [\App\Http\Controllers\FormController::class, 'index']);
