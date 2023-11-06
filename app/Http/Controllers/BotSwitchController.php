<?php

namespace App\Http\Controllers;

use App\Models\TelegramBot;

class BotSwitchController extends Controller
{
    public function __invoke(TelegramBot $bot)
    {
        $user = auth()->user();
        if($user && $user->telegram_bots->contains($bot) || $user->hasRole('supervisor')) {
            session(['bot' => $bot->id]);
            return back()->with('success', __('webapp.bot_chosen', ['bot' => $bot->name]));
        } else {
            return back()->with('error', __('webapp.bot_chosen_error', ['bot' => $bot->name]));
        }
    }
}
