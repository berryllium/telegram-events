<?php

namespace App\Http\Middleware;

use App\Models\TelegramBot;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionBot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()) {
            if(session('bot') && !$request->user()->hasRole('supervisor') && !$request->user()?->telegram_bots->contains(session('bot'))) {
                auth()->logout();
            } elseif(!session('bot')) {
                $bot = $request->user()->telegram_bots->count() ? $request->user()->telegram_bots->first() : TelegramBot::first();
                session('bot', $bot->id);
            }
        }

        return $next($request);
    }
}
