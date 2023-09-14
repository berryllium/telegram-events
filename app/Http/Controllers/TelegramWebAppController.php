<?php

namespace App\Http\Controllers;

use App\Actions\Telegram\TelegramRequestHandler;
use App\Models\Form;

class TelegramWebAppController extends Controller
{
    public function index(Form $form) {
        return view('telegram.webapp.index', [
            'form' => $form,
        ]);
    }
}
