<?php

namespace App\Http\Controllers\WebApp;

use App\Actions\Telegram\TelegramFormHandler;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebAppController extends Controller
{
    public function index(Request $request, TelegramBot $telegramBot)
    {
        $author = Author::find($request->get('author'));
        if($author && $author->places->count()) {
            $places = $telegramBot->places->intersect($author->places);
        }

        return view('webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form,
            'places' => $places ?? $telegramBot->places,
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        Log::info('webapp', $request->toArray());
        return $formHandler->handle($request, $telegramBot);
    }

}
