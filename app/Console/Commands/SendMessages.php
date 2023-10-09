<?php

namespace App\Console\Commands;

use App\Facades\TechBotFacade;
use App\Models\MessageFile;
use App\Models\MessageSchedule;
use App\Models\TelegramBot;
use App\Services\VKService;
use CURLFile;
use DigitalStar\vk_api\vk_api;
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
        $this->mainBot = new BotApi(config('app.service_bot.token'));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageSchedules = MessageSchedule::query()
            ->where('status', '=', 'wait')
            ->where('sending_date', '<=', now())
            ->whereHas('message', fn($q) => $q->where('allowed', true))
            ->limit(100)
            ->get();

        $botApis = [];

        foreach ($messageSchedules as $messageSchedule) {
            try {
                foreach ($messageSchedule->channels as $channel) {
                    if($channel->type == 'tg') {
                        $bot = $messageSchedule->message->telegram_bot;
                        if(!(isset($botApis[$bot->id]))) {
                            $botApis[$bot->id] = new BotApi($bot->api_token);
                        }
                        if($messageSchedule->message->message_files->count()) {
                            $mediaArr = TechBotFacade::createMedia($messageSchedule->message);
                            $botApis[$bot->id]->sendMediaGroup($channel->tg_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                        } else {
                            $botApis[$bot->id]->sendMessage($channel->tg_id, $messageSchedule->message->text, 'HTML');
                        }
                    } elseif($channel->type == 'vk') {
                        $vk = new VKService(config('app.vk_token'),$channel->tg_id,"");
                        if($messageSchedule->message->message_files->count()) {
                            foreach ($messageSchedule->message->message_files as $file) {
                                /** @var MessageFile $file */
                                if ($file->type == 'image') {
                                    $vk->addPhoto($file->path);
                                } elseif ($file->type == 'video') {
                                    $vk->addVideo($file->path);
                                }
                            }
                        }
                        $vk->Post(strip_tags($messageSchedule->message->text));
                        sleep(1);
                    }
                }
                $messageSchedule->status = 'success';
                $messageSchedule->error_text = null;
                $messageSchedule->save();
            } catch (\Exception $exception) {
                $error = 'Sending error ' .
                    $messageSchedule->message->error . ' ' .
                    $exception->getMessage();

                $messageSchedule->status = 'error';
                $messageSchedule->error_text = $error;
                $messageSchedule->save();
                $this->mainBot->sendMessage(config('app.service_bot.channel'),  $exception->getMessage());
            }
        }
    }
}
