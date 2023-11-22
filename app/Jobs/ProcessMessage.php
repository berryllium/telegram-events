<?php

namespace App\Jobs;

use App\Facades\TechBotFacade;
use App\Models\Channel;
use App\Models\Message;
use App\Models\MessageFile;
use App\Models\MessageSchedule;
use App\Services\VKService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidArgumentException;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected Message $message;
    protected $preparedText;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected MessageSchedule $messageSchedule,
        protected Channel $channel,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->message = $this->messageSchedule->message;
            $this->prepareText();
            if($this->queue == 'vk') {
                $link = $this->sendVK();
            } elseif($this->queue == 'tg') {
                $link = $this->sendTG();
            } else {
                $link = '';
            }
            $this->updateMessageStatus(link: $link);
        } catch (\Exception $exception) {
            $this->updateMessageStatus(error: $exception->getMessage());
            throw $exception;
        }
    }

    /**
     * @return string link
     * @throws \Exception
     */
    protected function sendVK(): string
    {
        $vk = new VKService(config('app.vk_token'),$this->channel->tg_id, '');
        try {
            if($this->message->message_files->count()) {
                foreach ($this->message->message_files as $file) {
                    /** @var MessageFile $file */
                    if ($file->type == 'image') {
                        $vk->addPhoto($file->path);
                    } elseif ($file->type == 'video') {
                        $vk->addVideo($file->path);
                    }
                }
            }

            return strtr('<a href="LINK">TEXT</a>', [
                'LINK' => $vk->Post(strip_tags($this->preparedText)),
                'TEXT' => __('webapp.tg_link_text')
            ]);
        } catch (\Exception $exception) {
            TechBotFacade::send(__('webapp.error_sending_vk', [
                'id' => $this->message->id,
                'channel' => $this->channel->name,
                'bot' => $this->message->telegram_bot->name
            ]));
            throw($exception);
        }

    }

    /**
     * @return string link
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    protected function sendTG() : string {
        Log::info('sending', ['tg']);
        $bot = new BotApi($this->message->telegram_bot->api_token);
        try {
            if($this->message->message_files->count()) {
                $mediaArr = TechBotFacade::createMedia($this->message);
                $tg_message = $bot->sendMediaGroup($this->channel->tg_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
            } else {
                $tg_message = $bot->sendMessage($this->channel->tg_id, $this->preparedText, 'HTML');
            }
            if(is_array($tg_message)) $tg_message = reset($tg_message);
            /** @var \TelegramBot\Api\Types\Message  $tg_message */
            return strtr('<a href="https://t.me/c/CID/MID">TEXT</a>', [
                'CID' => substr($tg_message->getChat()->getId(), 4),
                'MID' => $tg_message->getMessageId(),
                'TEXT' => __('webapp.tg_link_text')
            ]);
        } catch (\Exception $exception) {
            TechBotFacade::send(__('webapp.error_sending_tg', [
                'id' => $this->message->id,
                'channel' => $this->channel->name,
                'bot' => $this->message->telegram_bot->name
            ]));
            throw($exception);
        }

    }

    protected function updateMessageStatus($link = null, $error = null)
    {
        $this->messageSchedule->channels()->updateExistingPivot($this->channel->id, [
            'error' => $error,
            'link' => $link,
            'sent' => true
        ]);
        $this->messageSchedule->updateStatus();
    }

    private function prepareText(): void
    {
        $text = $this->message->text;
        if(!$this->channel->show_place) {
            $text = preg_replace("/.*🏢.*(\n\n)*/u", "", $text);
        }
        if(!$this->channel->show_address) {
            $text = preg_replace("/.*📍.*(\n\n)*/u", "", $text);
        }
        $this->preparedText = $text;
    }
}
