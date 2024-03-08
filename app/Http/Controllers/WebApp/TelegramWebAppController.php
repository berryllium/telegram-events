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
        $can_select_channels = false;
        $channels = [];

        if($author) {
            $places = $telegramBot->places->intersect($author->places);
            $pivot = $author->telegram_bots->find($telegramBot)->pivot;
            if($pivot->can_select_channels) {
                $can_select_channels = true;
                $channels = $author->channels()->where('telegram_bot_id', $telegramBot->id)->get();
            }
        }

        $places = $places->count() ? $places : $telegramBot->places()->where('active', 1);

        return view('webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form,
            'places' => $places,
            'addresses' => $telegramBot->places()->select('id', 'address')->get(),
            'can_select_channels' => $can_select_channels,
            'channels' => $channels,
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        return $formHandler->handle($request, $telegramBot);
    }

}
