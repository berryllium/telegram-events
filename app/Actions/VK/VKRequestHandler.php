<?php

namespace App\Actions\VK;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TelegramBot\Api\BotApi;

class VKRequestHandler
{
    public function handle(Request $request)
    {
        try {
            $data = $request->toArray();
            Log::info('vk push - ' . count($data), $data);

            if($request->get('secret') != config()->get('app.vk_group_secret')) {
                return;
            } elseif ($data['type'] == 'wall_reply_new') {
                $channels = Channel::query()
                    ->where('tg_id', $data['group_id'])
                    ->where('type', 'vk')
                    ->get();

                if ($channels->count() < 1) {
                    Log::info('Cannot find channel ' . $data['group_id']);
                } else {
                    foreach ($channels as $channel) {
                        $channel_link = "https://vk.com/wall-{$data['group_id']}";
                        $message = (__('webapp.comments.vk', [
                            'link' => $channel_link . '_' . $data['object']['post_id'],
                            'text' => Str::of($data['object']['text'])->words(10, '...'),
                            'channel' => $channel ? $channel->name : 'Unknown group',
                            'channel_link' => $channel_link,
                            'date' => date('d.m.Y H:i:s', $data['object']['date']),
                        ]));

                        if($channel->telegram_bot->comments_channel_id) {
                            $botApi = new BotApi($channel->telegram_bot->api_token);
                            $botApi->sendMessage($channel->telegram_bot->comments_channel_id, $message, 'HTML', true);
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::info('vk - push',['error' =>  $exception->getMessage()]);
        }

    }
}