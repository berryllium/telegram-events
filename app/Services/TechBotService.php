<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageFile;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\InputMedia\InputMediaVideo;

class TechBotService
{
    private $client;
    private $channel;

    public function __construct()
    {
        $this->client = new BotApi(env('SERVICE_BOT_TOKEN'));
        $this->channel = env('SERVICE_BOT_CHANNEL');
    }

    public function send(string $message) {
        $this->client->sendMessage($this->channel, $message);
    }

    public function createMedia(Message $message) {
        $media = new ArrayOfInputMedia();
        $needCaption = true;
        $attachments = [];
        foreach ($message->message_files as $file) {
            /** @var MessageFile $file */
            $attachments[$file->filename] = new \CURLFile($file->path);

            if($needCaption) {
                $caption = $message->text;
                $parseMode = 'HTML';
                $needCaption = false;
            } else {
                $caption = $parseMode = null;
            }

            if($file->type == 'video'){
                $media->addItem(new InputMediaVideo('attach://' . $file->filename, $caption, $parseMode));
            }elseif($file->type == 'image'){
                $media->addItem(new InputMediaPhoto('attach://' . $file->filename, $caption, $parseMode));
            }
        }
        return [
            'media' => $media,
            'attachments' => $attachments
        ];
    }

}
