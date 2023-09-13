<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Place;

class TelegramWebAppController extends Controller
{
    public function index(Form $form) {
        return view('telegram.webapp.index', [
            'form' => $form,
        ]);
    }
}
