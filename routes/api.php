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
Route::post('/tags/{place}', [\App\Http\Controllers\API\TagController::class, 'getPlaceTagSets'])->name('api.place_tags');
Route::post('/gigachat/generate', [\App\Http\Controllers\API\GigaChatController::class, 'generate'])->name('api.giga');
Route::post('/gigachat/refresh-image', [\App\Http\Controllers\API\GigaChatController::class, 'refreshImage'])->name('api.giga_refresh_image');

Route::post('git', \App\Http\Controllers\API\GitController::class)->name('api.git');
Route::post('vk', \App\Http\Controllers\API\VKController::class)->name('api.vk');
Route::post('ok', \App\Http\Controllers\API\OKController::class)->name('api.ok');

Route::middleware('token')->group(function(){
    Route::get('/place/{place}/info', [\App\Http\Controllers\API\PlaceController::class, 'info'])->name('api.place.info');
    Route::get('/place/{place}/messages', [\App\Http\Controllers\API\PlaceController::class, 'messages'])->name('api.place.messages');
    Route::get('/place/{place}/services', [\App\Http\Controllers\API\PlaceController::class, 'services'])->name('api.place.services');
    Route::get('/place/{place}/message/{messageSchedule}', [\App\Http\Controllers\API\PlaceController::class, 'message'])->name('api.place.message');
});
