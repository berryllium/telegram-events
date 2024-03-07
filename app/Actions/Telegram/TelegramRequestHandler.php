<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Author;
use App\Models\Channel;
use App\Models\Message;
use App\Models\MessageSchedule;
use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TelegramRequestHandler
{
    public function handle(Request $request) {
        try {
            $data = $request->toArray();
            $token = $request->header('X-Telegram-Bot-Api-Secret-Token');
            Log::info('Telegram message ', $data);

            $bot = TelegramBot::query()->where('code', '=', $token)->first();
            if(!isset($data['message'])) {
                return response()->json(['status' => 'ok']);
            }

            $chat_id = $data['message']['chat']['id'] ?? null;
            $sender = $data['message']['from'] ?? null;
            $text = $data['message']['text'] ?? null;
            $web_app_data = $data['message']['web_app_data']['data'] ?? null;

            $author_name = $sender['first_name'] ?? $sender['username'] ?? 'new author';
            $author = Author::query()->where('tg_id', $sender['id'])->first();
            if(!$author) {
                $author = new Author([
                    'name' => $author_name,
                    'username' => $sender['username'] ?? 'unknown',
                    'tg_id' => $sender['id'],
                    'premium' => $sender['is_premium'] ?? false,
                ]);
                $author->save();
            }

            if(!$author->telegram_bots()->get()->contains($bot->id)) {
                $author->telegram_bots()->attach($bot, ['title' => $author_name]);
            }

            if($text == '/start') {
                $this->sendButton($chat_id, $bot, $author);
            } elseif($web_app_data) {
                Log::info('$web_app_data', [$web_app_data]);
                $web_app_data = json_decode($web_app_data, true);
                $message = Message::query()->find($web_app_data['message_id']);
                if(!$message) {
                    throw new \Exception('message not found');
                }
                $message->author_id = $author->id;
                $trusted = $author->telegram_bots()
                    ->wherePivot('trusted', true)
                    ->wherePivot('telegram_bot_id', $bot->id)
                    ->exists();
                $message->allowed = $trusted;
                $message->save();

                if(isset($message->data->place)) {
                    $place = Place::find($message->data->place);

                    if($author->places()->where('telegram_bot_id', $bot->id)->count() < 1) {
                        $author->places()->attach($message->data->place);
                    }

                    if(isset($message->data->all_channels) && $message->data->all_channels) {
                        $channels = $author->channels()->where('telegram_bot_id', $message->telegram_bot_id)->get();
                    } elseif(isset($message->data->channels) && $message->data->channels) {
                        $channels = $message->data->channels;
                    }else {
                        $channels = $place->channels;
                    }

                    $channels = $this->checkChannels($channels, $bot->id);
                    if($channels && $channels->count()) {
                        $publish_dates = $this->preparePublishDates($message->data->schedule);
                        foreach ($publish_dates as $date) {
                            /** @var MessageSchedule $messageSchedule */
                            $messageSchedule = $message->message_schedules()->create([
                                'sending_date' => $date
                            ]);
                            $messageSchedule->channels()->attach($channels);
                        }
                    }
                } else {
                    TechBotFacade::send('Place not found ' . $message->id);
                }

                $botApi = new BotApi($bot->api_token);
                $pivot = $author->telegram_bots()->wherePivot('telegram_bot_id', $bot->id)->first()->pivot;
                $admin_text = str_replace(
                    ['#author_type#', '#author_link#', '#message_link#', '#publish_date#'],
                    [
                        $pivot->trusted ? __('webapp.trusted_author') : __('webapp.user'),
                        "<a href='" . route('author.edit', $author->id) ."'>" . $pivot->title . "</a>",
                        "<a href='" . route('message.edit', $message->id) ."'>".__('webapp.message')."</a>",
                        implode("\r\n", $publish_dates ?? [])
                    ],
                    "#author_type# #author_link# ".__('webapp.has_posted')." #message_link#\r\n\r\n" . __('webapp.publish_date') . ":\r\n#publish_date#"
                );

                try {
                    if ($message->message_files->count()) {
                        /** @var Message $message */
                        $mediaArr = TechBotFacade::createMedia($message);
                        $botApi->sendMediaGroup($chat_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                        $botApi->sendMediaGroup($message->telegram_bot->moderation_group, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                    } else {
                        $botApi->sendMessage($chat_id, $message->text, 'HTML', true);
                        $botApi->sendMessage($message->telegram_bot->moderation_group, $message->text, 'HTML', true);
                    }

                    $botApi->sendMessage($message->telegram_bot->moderation_group, $admin_text, 'HTML', true);
                    $botApi->sendMessage($chat_id, __('webapp.message_accepted') . " #" . $web_app_data['message_id'], 'HTML', true);
                } catch (HttpException $exception) {
                    $msg_error = __('webapp.error_sending_moderation', [
                        'id' => $message->id,
                        'g_id' => $message->telegram_bot->moderation_group,
                        'bot' => $message->telegram_bot->name
                    ]);
                    Log::error($msg_error);
                    TechBotFacade::send($msg_error);
                }
            } elseif(isset($data['message']['reply_to_message']) && config('app.service_bot.comments_notification')) {
                $this->handleComment($data);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            TechBotFacade::send(implode(', ', [$exception->getMessage() ,$exception->getFile(), $exception->getLine()]));
        }

        return response()->json(['status' => 'ok']);
    }

    public function sendButton($chat_id, $bot, $author) {
        $keyboard = new ReplyKeyboardMarkup(
            [
                [
                    ['text' => __('webapp.add_post'), 'web_app' => ['url'=> route('webapp', ['telegram_bot' => $bot, 'author' => $author])]]
                ]
            ]
        );
        $botApi = new BotApi($bot->api_token);
        $botApi->sendMessage($chat_id, __('webapp.greeting'), null, true, null, $keyboard);
    }

    private function preparePublishDates(array $dates): array
    {
        $result = [];
        $dates = array_unique($dates);
        foreach ($dates as $k => $date) {
            $dates[$k] = $date ? Carbon::parse($date) : now();
        }
        sort($dates);

        $last = null;
        foreach ($dates as $date) {
            if(!$last) {
                $last = $date;
            } elseif($date->diffInSeconds($last) < 300) {
                continue;
            }
            $result[] = $date;
        }

        return $result;
    }

    private function handleComment($data) : void
    {
        try {
            if(cache('last_comment') != $data['message']['message_id']) {
                cache(['last_comment' => $data['message']['message_id']], now()->addMinutes(10));
                $msg_id = $data['message']['reply_to_message']['message_id'];
                $channel = Channel::query()->where('tg_id', $data['message']['reply_to_message']['sender_chat']['id'])->first();
                if($channel && $channel->telegram_bot->comments_channel_id) {
                    $botApi = new BotApi($channel->telegram_bot->api_token);
                    $cid = substr($data['message']['chat']['id'], 4);
                    $text = "Новый <a href=\"https://t.me/c/{$cid}/{$msg_id}\">комментарий</a> в канале {$channel->name}";
                    $botApi->sendMessage($channel->telegram_bot->comments_channel_id, $text, 'HTML', true);
                }
            }
        } catch (\Exception $exception) {
            Log::error('Comment Telegram Error', ['error' => $exception->getMessage()]);
        }
    }

    private function checkChannels($channels, $bot_id)
    {
        $result = new Collection();
        foreach ($channels as $channel) {
            /** @var Channel $channel */
            if($channel->telegram_bot_id === $bot_id) {
                $result->add($channel);
            }
        }
        return $result;
    }
}