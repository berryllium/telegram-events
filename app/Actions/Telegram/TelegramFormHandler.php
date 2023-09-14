<?php

namespace App\Actions\Telegram;

use App\Models\TelegramBot;
use Illuminate\Http\Request;
use TelegramBot\Api\BotApi;

class TelegramFormHandler
{
    public function __construct()
    {
        $this->client = new BotApi('6693099766:AAF45rcSrSzvUapg7IUpazpkIKhUABbUwho');
    }

    public function handle(Request $request, TelegramBot $telegramBot) {
        $message = $telegramBot->messages()->create([
            'text' => json_encode($request->toArray()),
            'allowed' => false
        ]);
        return response()->json(['message_id' => $message->id]);
    }

}
