<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Author;
use App\Models\Message;
use App\Models\MessageSchedule;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramRequestHandler
{
    public function handle(Request $request) {
        TechBotFacade::send('обработка сообщения...');
        try {
            $data = $request->toArray();
            $token = $request->header('X-Telegram-Bot-Api-Secret-Token');
            Log::info('Сообщение от телеграм ', $data);

            $bot = TelegramBot::query()->where('code', '=', $token)->first();

            $chat_id = $data['message']['chat']['id'] ?? null;
            $sender = $data['message']['from'] ?? null;
            $text = $data['message']['text'] ?? null;
            $web_app_data = $data['message']['web_app_data']['data'] ?? null;

            if($text == '/start') {
                $this->sendButton($chat_id, $bot);
            } elseif($web_app_data) {
                Log::info('$web_app_data', [$web_app_data]);
                $web_app_data = json_decode($web_app_data, true);
                $message = Message::query()->find($web_app_data['message_id']);
                $author = Author::query()->where('tg_id', $sender['id'])->first();
                if(!$author) {
                    $author = new Author();
                    $author->name = $sender['first_name'] ?? 'unknown';
                    $author->username = $sender['username'] ?? 'unknown';
                    $author->tg_id = $sender['id'];
                    $author->premium = $sender['is_premium'] ?? false;
                    $author->save();
                }
                $message->author_id = $author->id;
                $message->save();
                foreach ($message->data->schedule as $date) {
                    /** @var MessageSchedule $messageSchedule */
                    $messageSchedule = $message->message_schedules()->create([
                        'sending_date' => $date ? Carbon::parse($date) : now()
                    ]);
                    $messageSchedule->telegram_channels()->attach(1);
                }

                $botApi = new BotApi($bot->api_token);

                $botApi->sendMessage($chat_id, "Ваше сообщение принято! #" . $web_app_data['message_id']);
                $botApi->sendMessage($chat_id, $message->text, 'HTML');
                $botApi->sendMessage($message->telegram_bot->moderation_group, $message->text, 'HTML');
            }
        } catch (\Exception $exception) {
            TechBotFacade::send($exception->getMessage(), $exception->getTraceAsString());
        }

        return response()->json(['status' => 'ok']);
    }

    public function sendButton($chat_id, $bot) {
        $keyboard = new ReplyKeyboardMarkup(
            [
                [
                    ['text' => '🔖 Разместить пост ', 'web_app' => ['url'=> route('webapp', $bot)]]
                ]
            ]
        );
        $botApi = new BotApi($bot->api_token);
        $botApi->sendMessage($chat_id, 'Приветствую! Воспользуйтесь кнопкой для заполнения формы!', null, false, null, $keyboard);
    }

}