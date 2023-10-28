<?php

namespace App\Listeners;


use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Http\Request;

class SetUserBot
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $bot = $user->telegram_bots()->first();
        if($bot) {
            session(['bot' => $bot->id]);
        } elseif($user->hasRole('supervisor')) {
            session(['bot' => TelegramBot::query()->first()]);
        }
    }
}
