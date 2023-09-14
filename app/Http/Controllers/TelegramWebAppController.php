<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;

class TelegramWebAppController extends Controller
{
    public function index(TelegramBot $telegramBot) {
        return view('telegram.webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form
        ]);
    }
}
