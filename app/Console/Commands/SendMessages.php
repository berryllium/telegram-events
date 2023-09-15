<?php

namespace App\Console\Commands;

use App\Models\MessageSchedule;
use App\Models\TelegramBot;
use Illuminate\Console\Command;
use TelegramBot\Api\BotApi;

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
                    $botApis[$bot->id]->sendMessage($channel->tg_id, $messageSchedule->message->text, 'HTML');
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
                $this->mainBot->sendMessage(168827230,  $exception->getMessage());
            }
        }
    }
}