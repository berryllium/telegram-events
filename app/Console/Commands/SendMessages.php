<?php

namespace App\Console\Commands;

use App\Models\MessageSchedule;
use App\Models\TelegramBot;
use CURLFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\InputMedia\InputMediaVideo;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected BotApi $mainBot;

    public function __construct()
    {
        parent::__construct();
        $this->mainBot = new BotApi(TelegramBot::find(1)->api_token);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageSchedules = MessageSchedule::query()
            ->where('status', '=', 'wait')
            ->limit(100)
            ->get();

        $botApis = [];

        foreach ($messageSchedules as $messageSchedule) {
            try {
                foreach ($messageSchedule->telegram_channels as $channel) {
                    $bot = $messageSchedule->message->telegram_bot;
                    if(!(isset($botApis[$bot->id]))) {
                        $botApis[$bot->id] = new BotApi($bot->api_token);
                    }
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
                        $botApis[$bot->id]->sendMediaGroup($channel->tg_id, $media, null, null, null, null, null, $attachments);
                    } else {
                        $botApis[$bot->id]->sendMessage($channel->tg_id, $messageSchedule->message->text, 'HTML');
                    }
                }
                $messageSchedule->status = 'success';
                $messageSchedule->error_text = null;
                $messageSchedule->save();
            } catch (\Exception $exception) {
                $error = 'Ошибка при отправке сообщения ' .
                    $messageSchedule->message->error . ' ' .
                    $exception->getMessage();

                $messageSchedule->status = 'error';
                $messageSchedule->error_text = $error;
                $messageSchedule->save();
                $this->mainBot->sendMessage(env('SERVICE_BOT_CHANNEL'),  $exception->getMessage());
            }
        }
    }
}
