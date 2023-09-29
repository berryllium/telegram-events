<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LanguageController extends Controller
{
    public function index(string $lang = 'ru') {
        return back()->withCookie(cookie('lang', $lang));
    }
}
