<?php

namespace App\Http\Controllers\API;

use App\Actions\Telegram\TelegramRequestHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramController extends Controller
{

    public function index(Request $request, TelegramRequestHandler $handler)
    {
        return $handler->handle($request);
    }

}
