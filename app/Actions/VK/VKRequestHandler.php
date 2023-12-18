<?php

namespace App\Actions\VK;

use App\Facades\TechBotFacade;
use App\Models\Author;
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

class VKRequestHandler
{
    public function handle(Request $request)
    {
        $data = $request->toArray();
        Log::info('vk push - ' . count($data), $data);
        Log::info('vk headers - ', $request->headers->all());

        if($request->get('secret') != config()->get('app.vk_group_secret')) {
            return;
        } elseif ($data['type'] == 'wall_reply_new') {
            TechBotFacade::send("Новый комментарий в группе {$data['group_id']} к посту {$data['object']['post_id']} : {$data['object']['text']}");
        }

    }
}