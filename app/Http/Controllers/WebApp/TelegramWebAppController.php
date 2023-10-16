<?php

namespace App\Http\Controllers\WebApp;

use App\Actions\Telegram\TelegramFormHandler;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TelegramWebAppController extends Controller
{
    public function index(Request $request, TelegramBot $telegramBot)
    {
        $author = Author::find($request->get('author'));
        $places = new Collection();
        if($author) {
            $places = $telegramBot->places->intersect($author->places);
        }

        return view('webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form,
            'places' => $places->count() ? $places : $telegramBot->places,
            'addresses' => $telegramBot->places()->select('id', 'address')->get()
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        return $formHandler->handle($request, $telegramBot);
    }

}
