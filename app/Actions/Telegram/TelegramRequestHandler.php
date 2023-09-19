<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Author;
use App\Models\Message;
use App\Models\MessageSchedule;
use App\Models\Place;
use App\Models\TelegramBot;
use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\InputMedia\InputMediaVideo;
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

                // TODO вынести в отдельный класс формирование media для сообщениея
                if($messageSchedule->message->message_files) {
                    $media = new ArrayOfInputMedia();
                    $needCaption = true;
                    $attachments = [];
                    foreach ($messageSchedule->message->message_files as $file) {
                        $mime = mime_content_type($file->path);
                        $attachments[$file->filename] = new CURLFile($file->path);

                        if($needCaption) {
                            $caption = $messageSchedule->message->text;
                            $parseMode = 'HTML';
                            $needCaption = false;
                        } else {
                            $caption = $parseMode = null;
                        }

                        if(strstr($mime, "video/")){
                            $media->addItem(new InputMediaVideo('attach://' . $file->filename, $caption, $parseMode));
                        }else if(strstr($mime, "image/")){
                            $media->addItem(new InputMediaPhoto('attach://' . $file->filename, $caption, $parseMode));
                        }

                    }
                    $botApi->sendMediaGroup($chat_id, $media, null, null, null, null, null, $attachments);
                    $botApi->sendMediaGroup($message->telegram_bot->moderation_group, $media, null, null, null, null, null, $attachments);
                } else {
                    $botApi->sendMessage($chat_id, $message->text, 'HTML');
                    $botApi->sendMessage($message->telegram_bot->moderation_group, $message->text, 'HTML');
                }

                $botApi->sendMessage($message->telegram_bot->moderation_group, $admin_text, 'HTML');
                $botApi->sendMessage($chat_id, "Ваше сообщение принято! #" . $web_app_data['message_id']);
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