<?php

namespace App\Listeners;


use Illuminate\Http\Request;

class SetUserBot
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $bot = $user->telegram_bots()->first();
        if($bot) {
            session(['bot' => $bot->id]);
        }
    }
}
