<?php

namespace App\Actions\Telegram;

use App\Models\Author;
use App\Models\Message;
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
        $this->client->sendMessage(168827230, 'обрабатываем');
        try {
            $data = $request->toArray();
            $token = $request->header('X-Telegram-Bot-Api-Secret-Token');
            Log::info('Сообщение от телеграм ', $data);

            $bot = TelegramBot::query()->where('code', $token)->first();
            $chat_id = $data['message']['chat']['id'];
            $sender = $data['message']['from'];
            $text = $data['message']['text'] ?? false;
            $web_app_data = $data['message']['web_app_data']['data'] ?? false;

            if($text == '/start') {
                $this->sendButton($chat_id, $bot['id']);
            } elseif($web_app_data) {
                Log::info('$web_app_data', [$web_app_data]);
                $web_app_data = json_decode($web_app_data, true);
                $message = Message::query()->find($web_app_data['message_id']);
                $author = Author::query()->where('tg_id', $sender['id'])->first();
                if(!$author) {
                    $author = new Author();
                    $author->name = $sender['first_name'];
                    $author->username = $sender['username'];
                    $author->tg_id = $sender['id'];
                    $author->premium = $sender['is_premium'];
                    $author->save();
                }
                $message->author_id = $author->id;
                $message->save();
                $this->client->sendMessage($chat_id, "Ваше сообщение принято! #" . $web_app_data['message_id']);
            }
        } catch (\Exception $exception) {
            $this->client->sendMessage(168827230, $exception->getMessage());
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