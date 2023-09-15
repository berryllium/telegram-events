<?php

namespace App\Services;

use TelegramBot\Api\BotApi;

class TechBotService
{
    private $client;
    private $channel;

    public function __construct()
    {
        $this->client = new BotApi(env('SERVICE_BOT_TOKEN'));
        $this->channel = env('SERVICE_BOT_CHANNEL');
    }

    public function send($message) {
        $this->client->sendMessage($this->channel, $message);
    }

}
