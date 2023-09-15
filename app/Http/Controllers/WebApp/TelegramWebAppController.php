<?php

namespace App\Http\Controllers\WebApp;

use App\Actions\Telegram\TelegramFormHandler;
use App\Http\Controllers\Controller;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebAppController extends Controller
{
    public function index(TelegramBot $telegramBot)
    {
        return view('telegram.webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        Log::info('webapp', $request->toArray());
        return $formHandler->handle($request, $telegramBot);
    }

}