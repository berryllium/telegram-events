<?php

namespace App\Http\Controllers;

use TelegramBot\Api\BotApi;

class TestController extends Controller
{
    public function __invoke()
    {
        $bot = new BotApi(config('app.service_bot.token'));
        $messageTgObj = $bot->sendMessage(-1001690629442, 'test', 'HTML');
        $link = strtr("https://t.me/c/CID/MID", [
            'CID' => substr($messageTgObj->getChat()->getId(), 4),
            'MID' => $messageTgObj->getMessageId()
        ]);

        dd($messageTgObj->getMessageId());
    }
}
