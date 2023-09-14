<?php

namespace App\Actions\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;

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


        $chat_id = $data['message']['chat']['id'];
        $name = $data['message']['from']['first_name'];
        if($data['message']['text'] == '/start') {
            $this->sendButton($chat_id);
        }

        $this->client->sendMessage($chat_id, "Привет, $name!");


        // TODO надо создать сущности телеграм ботов, по которым можно будет найти форму и ссылку на нее

        return response()->json(['status' => 'ok']);
    }

    public function sendButton($chat_id) {
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(
            [
                [
                    ['text' => '🔖 Разместить событие', 'web_app' => ['url'=>'https://telegram.chekhov-events.ru/telegram/webapp/1']]
                ]
            ]
        );
        $this->client->sendMessage($chat_id, 'Приветствую!', null, false, null, $keyboard);
    }




}