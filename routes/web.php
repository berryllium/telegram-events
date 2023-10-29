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

    Route::resource('/user', \App\Http\Controllers\UserController::class);
    Route::resource('/form', \App\Http\Controllers\FormController::class);
    Route::resource('/form/{form}/field', \App\Http\Controllers\FieldController::class);
    Route::resource('/channel', \App\Http\Controllers\ChannelController::class);
    Route::resource('/bot', \App\Http\Controllers\TelegramBotController::class);
    Route::resource('/place', \App\Http\Controllers\PlaceController::class);
    Route::resource('/message', \App\Http\Controllers\MessageController::class)->except(['create', 'store'])->withTrashed(['edit']);;
    Route::resource('/message_schedule', \App\Http\Controllers\MessageScheduleController::class)->except(['create', 'store', 'index'])->withTrashed(['edit']);
    Route::resource('/author', \App\Http\Controllers\AuthorController::class);
    Route::resource('/dictionary', \App\Http\Controllers\DictionaryController::class);

    Route::post('/form/copy/{form}', '\App\Http\Controllers\FormController@copy')->name('form.copy');

    Route::get('/', fn() => redirect('/message'));
    Route::get('/bot-switch/{bot}', \App\Http\Controllers\BotSwitchController::class)->name('bot_switch');

    Route::get('/report', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
    Route::get('/report/process', [\App\Http\Controllers\ReportController::class, 'process'])->name('report.process')->withTrashed();

    Route::get('/test', \App\Http\Controllers\TestController::class);
});

Route::get('/language/{lang}', [App\Http\Controllers\LanguageController::class, 'index'])->name('language');
Route::get('/telegram/webapp/{telegram_bot}', [\App\Http\Controllers\WebApp\TelegramWebAppController::class, 'index'])->name('webapp');

Route::post('/telegram/webapp/{telegram_bot}', [\App\Http\Controllers\WebApp\TelegramWebAppController::class, 'handleForm'])->name('webapp');