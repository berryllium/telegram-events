<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

class TelegramController extends Controller
{

    public function index(Request $request)
    {
        Log::info($request->getContent());

        $bot = new Client('6693099766:AAF45rcSrSzvUapg7IUpazpkIKhUABbUwho');


        $bot->command('ping', function ($message) use ($bot) {
            $bot->sendMessage($message->getChat()->getId(), 'pong!');
        });



        Log::info('no errors');
    }
}
