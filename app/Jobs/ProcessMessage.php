<?php

namespace App\Jobs;

use App\Facades\TechBotFacade;
use App\Models\Channel;
use App\Models\Message;
use App\Models\MessageFile;
use App\Models\MessageSchedule;
use App\Services\OKService;
use App\Services\VKService;
use App\Services\WPService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;
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
            } elseif($this->queue == 'ok') {
                $link = $this->sendOK();
            } elseif($this->queue == 'wp') {
                $link = $this->sendWP();
            } else {
                $link = '';
            }
            $this->updateMessageStatus(link: $link);
        } catch (\Throwable $exception) {
            $this->updateMessageStatus(error: $exception->getMessage() ?: 'error');
            Log::error($exception->getMessage(), ['exception' => $exception]);
        }
        sleep(1);
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
     * @throws \Exception
     */
    protected function sendOK(): string
    {
        $ok = new OKService($this->channel->tg_id);
        try {
            if($this->message->message_files->count()) {
                foreach ($this->message->message_files as $file) {
                    /** @var MessageFile $file */
                    if ($file->type == 'image') {
                        $ok->addPhoto($file->path);
                    } elseif ($file->type == 'video') {
                        $ok->addVideo($file->path);
                    }
                }
            }

            return strtr('<a href="LINK">TEXT</a>', [
                'LINK' => $ok->post(strip_tags($this->preparedText)),
                'TEXT' => __('webapp.tg_link_text')
            ]);
        } catch (\Exception $exception) {
            TechBotFacade::send(__('webapp.error_sending_ok', [
                'id' => $this->message->id,
                'channel' => $this->channel->name,
                'bot' => $this->message->telegram_bot->name
            ]));
            throw($exception);
        }

    }

    /**
     * @return string link
     * @throws \Exception
     */
    protected function sendWP(): string
    {
        $wp = new WPService($this->channel->tg_id, $this->channel->token);
        try {
            $media = [];
            if($this->message->message_files->count()) {
                foreach ($this->message->message_files as $file) {
                    /** @var MessageFile $file */
                    $media[$file->type][] = $file->src;
                }
            }

            return strtr('<a href="LINK">TEXT</a>', [
                'LINK' => $wp->post(strip_tags($this->preparedText), $this->message->data, $media),
                'TEXT' => __('webapp.tg_link_text')
            ]);
        } catch (\Exception $exception) {
            TechBotFacade::send(__('webapp.error_sending_wp', [
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
        $bot = new BotApi($this->message->telegram_bot->api_token);
        try {
            if($this->message->message_files->count()) {
                $mediaArr = TechBotFacade::createMedia($this->message, $this->preparedText);
                $tg_message = $bot->sendMediaGroup($this->channel->tg_id, $mediaArr['media'], null, null, null, null, null, $mediaArr['attachments']);
            } else {
                $tg_message = $bot->sendMessage($this->channel->tg_id, $this->preparedText, 'HTML', true);
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
            'sent' => true,
            'tries' =>  DB::raw('tries + 1'),
            'updated_at' => Carbon::now()
        ]);
        $this->messageSchedule->updateStatus();
    }

    private function prepareText(): void
    {
        $text = $this->message->text;
        if(!$this->channel->show_place) {
            $text = preg_replace("/.*🏢.*[\r\n]+\s?/um", "", $text);
        }
        if(!$this->channel->show_address) {
            $text = preg_replace("/.*📍.*[\r\n]+\s?/um", "", $text);
        }
        if(!$this->channel->show_work_hours) {
            $text = preg_replace("/.*🕒.*[\r\n]+\s?/um", "", $text);
        }
        if($this->channel->show_links) {
            Log::info('добавляю ссылки ' . $this->message->telegram_bot->links);
            $text .= "\r\n\r\n" . $this->message->telegram_bot->links;
        }
        $this->preparedText = $text;
    }
}
