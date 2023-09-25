<?php

namespace App\Console\Commands;

use App\Facades\TechBotFacade;
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
        $this->mainBot = new BotApi(env('SERVICE_BOT_TOKEN'));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageSchedules = MessageSchedule::query()
            ->where('status', '=', 'wait')
            ->whereHas('message', fn($q) => $q->where('allowed', true))
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
                    if($messageSchedule->message->message_files->count()) {
                        $mediaArr = TechBotFacade::createMedia($messageSchedule->message);
                        $botApis[$bot->id]->sendMediaGroup($channel->tg_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
                    } else {
                        $botApis[$bot->id]->sendMessage($channel->tg_id, $messageSchedule->message->text, 'HTML');
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
                $this->mainBot->sendMessage(env('SERVICE_BOT_CHANNEL'),  $exception->getMessage());
            }
        }
    }
}
