<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Author;
use App\Models\Message;
use App\Models\MessageSchedule;
use App\Models\Place;
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
                    $author = new Author([
                        'name' => $sender['first_name'] ?? 'unknown',
                        'username' => $sender['username'] ?? 'unknown',
                        'tg_id' => $sender['id'],
                        'premium' => $sender['is_premium'] ?? false,
                    ]);
                    $author->save();
                }
                $message->author_id = $author->id;
                $message->save();

                if(isset($message->data->place)) {
                    $place = Place::find($message->data->place);
                    $channels = $place->telegram_channels;

                    if($channels) {
                        foreach ($message->data->schedule as $date) {
                            /** @var MessageSchedule $messageSchedule */
                            $messageSchedule = $message->message_schedules()->create([
                                'sending_date' => $date ? Carbon::parse($date) : now()
                            ]);
                            $messageSchedule->telegram_channels()->attach($channels);
                        }
                    }
                } else {
                    TechBotFacade::send('Не найдено поле place в сообщении ' . $message->id);
                }

                $botApi = new BotApi($bot->api_token);

                $admin_text = str_replace(
                    ['#author_type#', '#author_link#', '#message_link#'],
                    [
                        $author->trusted ? 'Доверенный автор' : 'Пользователь',
                        "<a href='" . route('author.edit', $author->id) ."'>" . $author->name . "</a>",
                        "<a href='" . route('message.edit', $message->id) ."'>Сообщение</a>",
                    ],
                    "#author_type# #author_link# разместил #message_link#"
                );

                if($message->message_files->count()) {
                    $mediaArr = TechBotFacade::createMedia($message);
                    $botApi->sendMediaGroup($chat_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                    $botApi->sendMediaGroup($message->telegram_bot->moderation_group, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                } else {
                    $botApi->sendMessage($chat_id, $message->text, 'HTML');
                    $botApi->sendMessage($message->telegram_bot->moderation_group, $message->text, 'HTML');
                }

                $botApi->sendMessage($message->telegram_bot->moderation_group, $admin_text, 'HTML');
                $botApi->sendMessage($chat_id, "Ваше сообщение принято! #" . $web_app_data['message_id']);
            }
        } catch (\Exception $exception) {
            TechBotFacade::send(implode(', ', [$exception->getMessage(), $exception->getFile(), $exception->getLine()]));
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