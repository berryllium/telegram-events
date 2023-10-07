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
        return view('bot/index', ['bots' => TelegramBot::with('form')->paginate(20)]);
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
            'moderation_group' => 'int',
            'form_id' => 'required|int',
        ]);

        $bot = Telegrambot::create($data);

        if($bot->setWebhook()) {
            return redirect(route('bot.index'))->with('success', __('webapp.record_added'));
        } else {
            return redirect(route('bot.index'))->with('error', __('webapp.bot_created_without_binding'));
        }
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

        $bot->update($request->validate([
            'name' => 'required|min:2',
            'code' => 'required|alpha_dash:ascii|unique:telegram_bots,code,'.$bot->id,
            'api_token' => 'required',
            'moderation_group' => 'int',
            'description' => 'max:1000',
            'form_id' => 'required|int',
        ]));

        if($bot->setWebhook()) {
            return back()->with('success', __('webapp.record_updated'));
        } else {
            return back()->with('error', __('webapp.bot_created_without_binding'));
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TelegramBot $bot)
    {

        if($bot->unsetWebhook()) {
            return back()->with('error', __('webapp.bot_created_without_binding'));
        }

        $bot->delete();
        return redirect(route('bot.index'))->with('success', __('webapp.record_deleted'));
    }
}
