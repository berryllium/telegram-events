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
        $can_use_gigachat = false;
        $channels = [];

        if($author) {
            $places = $telegramBot->places->intersect($author->places);
            $pivot = $author->telegram_bots->find($telegramBot)->pivot;
            if($pivot->can_select_channels) {
                $can_select_channels = true;
                $channels = $author->channels()->where('telegram_bot_id', $telegramBot->id)->get();
            }
            if($pivot->can_use_gigachat) {
                $can_use_gigachat = true;
            }
        }

        $places = $places->count() ? $places : $telegramBot->places;
        $places = $places->filter(fn(Place $place) => !!$place->active);

        return view('webapp.index', [
            'bot' => $telegramBot,
            'form' => $telegramBot->form,
            'places' => $places,
            'addresses' => $telegramBot->places()->where('active', 1)->select('id', 'address')->get(),
            'can_select_channels' => $can_select_channels,
            'can_use_gigachat' => $can_use_gigachat,
            'channels' => $channels,
            'author' => $author
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        return $formHandler->handle($request, $telegramBot);
    }

}
