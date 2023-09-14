<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::middleware('auth')->group(function(){
    Route::resource('/form', \App\Http\Controllers\FormController::class);
    Route::resource('/form/{form}/field', \App\Http\Controllers\FieldController::class);
    Route::resource('/channel', \App\Http\Controllers\TelegramChannelController::class);
    Route::resource('/bot', \App\Http\Controllers\TelegramBotController::class);
    Route::resource('/place', \App\Http\Controllers\PlaceController::class);
    Route::get('/', [\App\Http\Controllers\FormController::class, 'index']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/telegram/webapp/{form}', [\App\Http\Controllers\TelegramWebAppController::class, 'index']);