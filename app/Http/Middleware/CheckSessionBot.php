<?php

namespace App\Http\Middleware;

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
        if(session('bot') && !$request->user()->hasRole('supervisor') && !$request->user()?->telegram_bots->contains(session('bot'))) {
            auth()->logout();
        }
        return $next($request);
    }
}
