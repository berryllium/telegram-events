<?php

namespace App\Actions\Telegram;

use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramRequestHandler
{
    private $client;

    public function __construct()
    {
        $this->client = new BotApi('6693099766:AAF45rcSrSzvUapg7IUpazpkIKhUABbUwho');
    }

    public function handle(Request $request) {
        $data = $request->toArray();
        $token = $request->header('X-Telegram-Bot-Api-Secret-Token');
        Log::info('Сообщение от телеграм ', $data);

        $bot = TelegramBot::query()->where('code', $token)->first();
        $chat_id = $data['message']['chat']['id'];
        $name = $data['message']['from']['first_name'];
        $text = $data['message']['text'] ?? false;
        $web_app_data = $data['message']['web_app_data']['data'] ?? false;

        if($text == '/start') {
            $this->sendButton($chat_id, $bot['id']);
        } elseif($web_app_data) {
            $this->client->sendMessage($chat_id, "Ваше сообщение принято! " . $web_app_data);
        }

        return response()->json(['status' => 'ok']);
    }

    public function sendButton($chat_id, $bot) {
        $keyboard = new ReplyKeyboardMarkup(
            [
                [
                    ['text' => '🔖 Разместить пост ', 'web_app' => ['url'=> route('webapp', $bot)]
                ]
            ]
        ]);
        $this->client->sendMessage($chat_id, 'Приветствую! Воспользуйтесь кнопкой для заполнения формы!', null, false, null, $keyboard);
    }

}