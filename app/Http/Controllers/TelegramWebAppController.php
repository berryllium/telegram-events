<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class TelegramWebAppController extends Controller
{
    public function index(Form $form) {
        return view('telegram.webapp.index', ['form' => $form]);
    }
}
