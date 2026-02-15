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
        if($author_id = $request->get('author')) {
            $author = Author::find($author_id);
        } elseif($user = $request->user()) {
            $author = $user->author;
        } else {
            return redirect('login');
        }

        if(!$author) {
            return response('Автор не найден', 404);
        }

        if(!$author->telegram_bots()->whereKey($telegramBot->id)->exists()) {
            return response('Данный бот не привязан к текущему пользователю. Обратитесь к администратору.', 404);
        }

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
            'author' => $author,
            'web_user' => $user ?? null,
        ]);
    }

    public function handleForm(Request $request, TelegramBot $telegramBot, TelegramFormHandler $formHandler)
    {
        return $formHandler->handle($request, $telegramBot);
    }

}
