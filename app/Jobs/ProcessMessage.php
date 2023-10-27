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
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidArgumentException;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected Message $message;

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
            if($this->queue == 'vk') {
                $this->sendVK();
            } elseif($this->queue == 'tg') {
                $this->sendTG();
            }
            $this->updateMessageStatus();
        } catch (\Exception $exception) {
            $this->updateMessageStatus($exception->getMessage());
            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    protected function sendVK() {
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
            $vk->Post(strip_tags($this->message->text));
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
     * @throws Exception
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    protected function sendTG() {
        $bot = new BotApi($this->message->telegram_bot->api_token);
        try {
            if($this->message->message_files->count()) {
                $mediaArr = TechBotFacade::createMedia($this->message);
                $bot->sendMediaGroup($this->channel->tg_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
            } else {
                $bot->sendMessage($this->channel->tg_id, $this->message->text, 'HTML');
            }
        } catch (HttpException $exception) {
            TechBotFacade::send(__('webapp.error_sending_tg', [
                'id' => $this->message->id,
                'channel' => $this->channel->name,
                'bot' => $this->message->telegram_bot->name
            ]));
            throw($exception);
        }

    }

    protected function updateMessageStatus($error = null)
    {
        $this->messageSchedule->channels()->updateExistingPivot($this->channel->id, [
            'error' => $error,
            'sent' => true
        ]);

        $sendings = $this->messageSchedule->channels()->wherePivot('message_schedule_id', $this->messageSchedule->id)->get();
        if($sendings) {
            $failed_channels = [];
            $error_text = '';
            $status = 'success';
            foreach ($sendings as $sending) {
                if(!$sending->pivot->sent) {
                    $status = 'process';
                }
                if($sending->pivot->error) {
                    $failed_channels[] = $sending->name;
                }
            }
            if($failed_channels) {
                $status = 'error';
                $error_text = __('webapp.error_sending_channels', ['channels' => implode(', ', $failed_channels)]);
            }
            $this->messageSchedule->update([
                'status' => $status,
                'error_text' => $error_text,
            ]);
        }
    }
}
