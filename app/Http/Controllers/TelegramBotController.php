<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Form;
use App\Models\TelegramBot;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramBotController extends Controller
{

    public function __construct() {
        $this->authorizeResource(TelegramBot::class, 'bot');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bot/index', [
            'bots' => TelegramBot::with('form')->paginate(20)->withQueryString()
        ]);
    }

    /**
     * Show the bot for creating a new resource.
     */
    public function create()
    {
        return view('bot/create', [
            'forms' => Form::query()->whereDoesntHave('bots')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bot = TelegramBot::create($request->validate([
            'name' => 'required|min:2',
            'code' => 'required|unique:telegram_bots|alpha_dash:ascii',
            'api_token' => 'required',
            'description' => 'max:1000',
            'moderation_group' => 'int',
            'form_id' => 'required|unique:telegram_bots,form_id',
        ]));

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
    public function edit(Telegrambot $bot, Request $request)
    {
        $botApi = new BotApi($bot->api_token);
        $botInfo = $botApi->getWebhookInfo();
        return view('bot/edit', [
            'bot' => $bot,
            'forms' => Form::query()->where('id', $bot->form->id)->orWhereDoesntHave('bots')->get(),
            'info' => $botInfo
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
            'form_id' => 'required|unique:telegram_bots,form_id,'.$bot->id,
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

    public function pin(TelegramBot $bot, Request $request) {

        $data = $request->validate([
            'pin_channel' => 'required|int',
            'pin_button' => 'required',
            'pin_text' => 'required',
        ]);

        try {
            $botApi = new BotApi($bot->api_token);
            $keyboard = new InlineKeyboardMarkup(
                [
                    [
                        ['text' => $data['pin_button'], 'url'=> "https://t.me/{$botApi->getMe()->getUsername()}"]
                    ]
                ]
            );
            $message = $botApi->sendMessage($data['pin_channel'], $data['pin_text'], null, false, null, $keyboard);
            $botApi->pinChatMessage($data['pin_channel'], $message->getMessageId());
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
        return back()->with('success', __('webapp.pin_success'));
    }
}
