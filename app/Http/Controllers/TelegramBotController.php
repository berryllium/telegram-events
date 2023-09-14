<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;

class TelegramBotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('bot/index', ['bots' => TelegramBot::paginate(20)]);
    }

    /**
     * Show the bot for creating a new resource.
     */
    public function create()
    {
        return view('bot/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:2',
            'code' => 'required|unique:telegram_bots|alpha_dash:ascii',
            'api_token' => 'required',
            'description' => 'max:1000',
            'form' => ''
        ]);

        if($data['form']) {
            $form = Form::find($data['form']);
            $form->bots()->create($data);
        } else {
            Telegrambot::create($data);
        }

        $botApi = new BotApi($data['api_token']);
        $res = $botApi->setWebhook(
            'https://telegram.chekhov-events.ru/api/telegram',
            null,
            null,
            40,
            null, 
            false,
            $data['code']);

        return redirect(route('bot.index'))->with('success', 'Бот успешно создан! ' .print_r($res, true));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the bot for editing the specified resource.
     */
    public function edit(Telegrambot $bot)
    {
        return view('bot/edit', [
            'bot' => $bot
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelegramBot $bot)
    {
        $bot->form_id = $request->get('form');

        $bot->update($request->validate([
            'name' => 'required|min:2',
            'code' => 'required|alpha_dash:ascii|unique:telegram_bots,code,'.$bot->id,
            'api_token' => 'required',
            'description' => 'max:1000',
        ]));

        $botApi = new BotApi($bot->api_token);
        $res = $botApi->setWebhook(
            'https://telegram.chekhov-events.ru/api/telegram',
            null,
            null,
            40,
            null,
            false,
            $request->get('code'));

        return redirect(route('bot.index'))->with('success', 'Бот успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TelegramBot $bot)
    {
        $bot->delete();
        return redirect(route('bot.index'))->with('success', 'Бот успешно удален!');
    }
}
