<?php

namespace App\Listeners;


use Illuminate\Http\Request;

class SetUserBot
{
    private $bot;
    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->bot = $request->input('telegram_bot');
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        if($this->bot && ($user->hasRole('supervisor') || $user->telegram_bots->contains($this->bot))) {
            session(['bot' => $this->bot]);
        }
    }
}
