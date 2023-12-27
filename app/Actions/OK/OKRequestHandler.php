<?php

namespace App\Actions\OK;

use App\Models\Channel;
use App\Services\OKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;

class OKRequestHandler
{
    public function handle(Request $request)
    {
        Log::info('ok test push - ', $request->toArray());
        try {
            $data = $request->toArray();
            if($data['webhookType'] == 'MESSAGE_CREATED') {
                $group_id = OKService::getGroupIdByChatID($data['recipient']['chat_id']);
                Log::info('ok group_id is ' . $group_id);
            }
            
            $channels = Channel::query()
                ->where('tg_id', $group_id)
                ->where('type', 'ok')
                ->get();

            if ($channels->count() < 1) {
                Log::info('Cannot find channel ' . $group_id);
            } else {
                foreach ($channels as $channel) {
                    $message = (__('webapp.comments.ok', [
                        'channel_link' => "https://ok.ru/group/$channel->tg_id/",
                        'channel' => $channel ? $channel->name : 'Unknown group',
                        'link' => OKService::getChatUrl($data['recipient']['chat_id']),
                        'date' => date("d.m.Y H:i:s", intval($data['timestamp'] / 1000)),
                        'sender' => $data['sender']['name'],
                        'text' => $data['message']['text'],
                    ]));

                    if($channel->telegram_bot->comments_channel_id) {
                        $botApi = new BotApi($channel->telegram_bot->api_token);
                        $botApi->sendMessage($channel->telegram_bot->comments_channel_id, $message, 'HTML', true);
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::info('ok - push',['error' =>  $exception->getMessage()]);
        }

    }
}