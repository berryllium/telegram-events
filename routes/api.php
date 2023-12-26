<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/telegram', [\App\Http\Controllers\API\TelegramController::class, 'index'])->name('api.telegram');
Route::post('/place/{place}/tags', [\App\Http\Controllers\API\TagController::class, 'getPlaceTagSets'])->name('api.place_tags');

Route::post('git', \App\Http\Controllers\API\GitController::class)->name('api.git');
Route::post('vk', \App\Http\Controllers\API\VKController::class)->name('api.vk');
Route::post('ok', \App\Http\Controllers\API\OKController::class)->name('api.ok');