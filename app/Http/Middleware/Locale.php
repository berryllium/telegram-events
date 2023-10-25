<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    const LOCALES = ['en', 'ru'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->cookie('lang')) {
            $lang = $request->cookie('lang');
        } elseif($accept_lang = $request->server('HTTP_ACCEPT_LANGUAGE')) {
            $accept_lang = explode(';', $accept_lang);
            $accept_lang = reset($accept_lang);
            $accept_lang = explode(',', $accept_lang);
            foreach ($accept_lang as $value) {
                if(in_array($value, self::LOCALES)) {
                    $lang = $value;
                }
            }
        }
        // you can use $lang instead of Config in the Feature
        App::setLocale(Config('app.locale'));
        return $next($request);
    }
}
