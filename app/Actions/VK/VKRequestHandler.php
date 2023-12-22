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
                $channel = Channel::query()
                    ->where('tg_id', $data['group_id'])
                    ->where('type', 'vk')
                    ->first()
                ;

                if($channel) {
                    $channel_link = "https://vk.com/wall-{$data['group_id']}";
                    $message = (__('webapp.comments.vk', [
                        'link' => $channel_link . '_' . $data['object']['post_id'],
                        'text' => Str::of($data['object']['text'])->words(10, '...'),
                        'channel' => $channel ? $channel->name : 'Unknown group',
                        'channel_link' => $channel_link,
                    ]));

                    $botApi = new BotApi($channel->telegram_bot->api_token);
                    $botApi->sendMessage($channel->telegram_bot->moderation_group, $message, 'HTML');
                } else {
                    Log::info('Cannot find channel ' . $data['group_id']);
                }
            }
        } catch (\Exception $exception) {
            Log::info('vk - push',['error' =>  $exception->getMessage()]);
        }

    }
}