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
            $this->preparedText = self::prepareText($this->message->text, $this->channel, $this->message->telegram_bot->links);
            if($this->queue == 'vk') {
                $link = $this->sendVK();
            } elseif($this->queue == 'tg') {
                $link = $this->sendTG();
            } elseif($this->queue == 'ok') {
                $link = $this->sendOK();
            } elseif($this->queue == 'wp') {
                $link = $this->sendWP();
            } elseif($this->queue == 'site') {
                $link = $this->sendSite();
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
            Log::error('OK - error', ['exception' => $exception]); 
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

    /**
     * @return string link
     * @throws \Exception
     */
    protected function sendSite(): string
    {
        try {
            // Get the place by its ID (stored in tg_id for site type channels)
            $place = \App\Models\Place::find($this->channel->tg_id);
            if (!$place || !$place->domain) {
                throw new \Exception('Place not found or domain not set for site channel');
            }

            return strtr('<a href="LINK">TEXT</a>', [
                'LINK' => 'https://' . $place->domain . '/detail/' . $this->message->id,
                'TEXT' => __('webapp.tg_link_text')
            ]);
        } catch (\Exception $exception) {
            TechBotFacade::send(__('webapp.error_sending_site', [
                'id' => $this->message->id,
                'channel' => $this->channel->name,
                'bot' => $this->message->telegram_bot->name
            ]));
            throw($exception);
        }
    }

    public static function prepareText($text, $channel, $links): string
    {
        if(!$channel->show_place) {
            $text = preg_replace("/^🏢.*[\r\n]+\s?/um", "", $text);
        }
        if(!$channel->show_address) {
            $text = preg_replace("/^📍.*[\r\n]+\s?/um", "", $text);
        }
        if(!$channel->show_work_hours) {
            $text = preg_replace("/^🕒.*[\r\n]+\s?/um", "", $text);
        }
        if($channel->show_links) {
            Log::info('добавляю ссылки места: ' . $links);
            $text .= "\r\n\r\n" . $links;
        }
        
        Log::debug('добавляю ссылки канала: ' .  $channel->name, ['links' => $channel->links]);
        $linksBlock = '';
        foreach($channel->links as $link) {
            $link_text = $link->name;

            if (preg_match('/#(.*?)#/', $link_text)) {
                $link_text = preg_replace_callback('/#(.*?)#/', function ($matches) use($link) {
                    $label = $matches[1];
                    return "<a href=\"{$link->link}\">$label</a>";
                }, $link_text);
            } else {
                $link_text = "<a href=\"{$link->link}\">$link_text</a>";
            }

            $linksBlock .= $link_text . "\r\n";
        }
        $linksBlock = $linksBlock ? $linksBlock : "\r\n" . $linksBlock . "\r\n";
        $text = str_replace('#channel_links#', $linksBlock, $text);
        Log::debug('добавляю ссылки канала: ' . $linksBlock);
        
        // пустых строк подряд должно быть не больше одной
        $text = preg_replace("/(\r?\n){2,}/", "\r\n\r\n", $text);
        return trim($text);
    }
}
