<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;

class TelegramWebAppController extends Controller
{
    public function index(TelegramBot $telegramBot) {
        return view('telegram.webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot) {
        Log::info('webapp', $request->toArray());
        $botApi = new BotApi($telegramBot->api_token);
        $botApi->sendMessage(168827230, 'success');
        return response()->json(['data' => 'ok']);
    }
}
