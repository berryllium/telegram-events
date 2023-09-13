<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Place;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;

class TelegramWebAppController extends Controller
{
    public function index(TelegramChannel $telegramChannel) {
        return view('telegram.webapp.index', [
            'form' => $telegramChannel->form()->first(),
            'places' => Place::all()
        ]);
    }
}
