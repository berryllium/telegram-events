<?php

namespace App\Actions\Telegram;

use App\Facades\ImageCompressorFacade;
use App\Facades\TechBotFacade;
use App\Jobs\ProcessMessage;
use App\Models\Author;
use App\Models\Channel;
use App\Models\Field;
use App\Models\MessageFile;
use App\Models\Place;
use App\Models\TelegramBot;
use App\Rules\MultibyteLength;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TelegramFormHandler
{
    private $file_paths = [];

    public function handle(Request $request, TelegramBot $telegramBot) {
        try {
            Log::debug($request->toArray());
            $has_files = false;

            if($gigaChatImagePath = $request->input('gigachat-image')) {
                $this->file_paths[] = $gigaChatImagePath;
                $has_files = true;
            }

            if($request->hasFile('files')) {
                $has_files = true;
                if(count($request->file('files')) > 10) {
                    return response()->json(['error' => __('webapp.max_files_count')]);
                }

                $request->validate(
                    ['files.*' => 'mimes:jpeg,jpg,png,webp,mp4,avi,mkv'],
                );

                $files = $request->file('files');

                $totalSize = 0;
                foreach ($files as $file) {
                    $path = $file->store('public/media');
                    if(str_starts_with($file->getMimeType(), 'image/')) {
                        ImageCompressorFacade::compress(Storage::path($path));
                    }                    
                    $totalSize += filesize(Storage::path($path));
                    $this->file_paths[] = $path;
                }

                if($totalSize > 50 * 1024 * 1024) {
                    return response()->json(['error' => __('webapp.error_max_files_size', [
                        'limit' => config('app.post_max_files_size', 50),
                        'size' => round($totalSize / 1024 / 1024, 2)
                    ])]);
                }

            }
            $data = $request->toArray();
            $fields = [];

            foreach ($data['schedule'] as $date) {
                $now = Carbon::now();
                $date = $date ? Carbon::parse($date) : $now;
                if($date->lt($now)) {
                    $date = $now;
                }
                $diff = $date->diffInDays($now);
                if($diff >= config('app.messages_storage_period')) {
                    return response()->json(['error' => __('webapp.error_max_days', [
                        'days' => config('app.messages_storage_period')
                    ])]);
                }
            }

            foreach ($telegramBot->form->fields as $field) {
                if($field->code == 'files') continue;
                if(isset($data[$field->code]) && $data[$field->code]) {
                    $fields[$field->code] = $this->prepareField($field, $data[$field->code]);
                } else {
                    $fields[$field->code] = '';
                }
            }

            if(isset($data['price_type']) && $data['price_type']) {
                $fields['price'] = $data['price'] ?? '';
                $fields['price_from'] = $fields['price_to'] = '';
                if($data['price_type'] == 'free') {
                    $fields['price'] = __('webapp.price_free');
                } elseif($data['price_type'] == 'range') {
                    $fields['price_from'] = $fields['price'];
                    $fields['price_to'] = $data['price_to'];
                } elseif($data['price_type'] == 'min') {
                    $fields['price_from'] = $fields['price'];
                }
            }

            if(isset($fields['place']) && $fields['place']) {
                /** @var Place $place */
                $place = $fields['place'];
                $fields['place_working_hours'] = $place->working_hours;
                $fields['place_additional_info'] = $place->additional_info;
                $fields['place'] = $place->name;
                $eventPlace = Place::query()->where('id', $data['address'])->first();
                if($eventPlace) {
                    $fields['address'] = $eventPlace->address_link ?
                        "<a href=\"{$eventPlace->address_link}\">{$eventPlace->address}</a>" :
                        $eventPlace->address;
                } else {
                    $fields['address'] = '';
                }
            }

            if(isset($fields['only_date']) && $fields['only_date'] && $fields['date']) {
                $fields['date'] = Carbon::parse($fields['date'])->format('d.m.Y');
            }

            $text = Blade::render($telegramBot->form->template, $fields);
            $text = htmlspecialchars_decode($text);
            $max_length = $has_files ? config('app.post_max_message') : config('app.post_without_files_max_message');

            // выяснить ТГ каналы, в которые идет сообщение
            $author = Author::findOrFail($request->input('author'));
 
            if($request->input('all_channels')) {
                $channels = $author->channels()->where('telegram_bot_id', $telegramBot->id)->get();
            } elseif($request->input('channels')) {
                $channels = $telegramBot->channels()->whereIn('id', $request->input('channels'))->get();
            }else {
                $channels = $place->channels;
            }

            $channels = $channels->filter(fn(Channel $ch) => $ch->telegram_bot_id == $telegramBot->id && $ch->type == 'tg');
            
            if(empty($channels)) {
                $validator = Validator::make(
                    ['text' => $text],
                    ['text' => new MultibyteLength($max_length, $text . "\r\n\r\n" . $telegramBot->links)],
                );
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()->first()]);
                }
            } else {
                foreach($channels as $channel) {
    
                    // для каждого канала генерируем сообщение, чтобы проверить длину
                    $tmp_text = ProcessMessage::prepareText($text, $channel, $telegramBot->links);

                    $validator = Validator::make(
                        ['text' => $text],
                        ['text' => new MultibyteLength($max_length, $tmp_text)],
                    );
                    if ($validator->fails()) {
                        Log::error('Message lenght limit', ['max' => $max_length, 'lenght' => mb_strlen($tmp_text), 'text' => $tmp_text]);
                        return response()->json(['error' => $validator->errors()->first() . ' в канале ' . $channel->name ]);
                    }
                }
            }



            $message = $telegramBot->messages()->create([
                'data' => json_encode($data),
                'text' => $text,
                'place_id' => isset($place) ? $place->id : null,
                'allowed' => false,
            ]);

            if($this->file_paths) {
                foreach ($this->file_paths as $path) {
                    $message->message_files()->save(new MessageFile(['filename' => $path]));
                }
            }

        } catch (\Exception $exception) {
            foreach ($this->file_paths as $path) {
                Storage::delete($path);
            }
            TechBotFacade::send($exception->getMessage());
            return response()->json(['error' => $exception->getMessage()]);
        }

        return response()->json(['message_id' => $message->id]);
    }

    private function prepareField(Field $field, string $value)
    {
        if($field->type == 'date') {
            return Carbon::parse($value)->format('d.m.Y H:i:s');
        } elseif($field->type == 'checkbox') {
            return $value ? 'Да' : 'Нет';
        }  elseif($field->type == 'place') {
            return Place::query()->find($value) ?? null;
        } elseif($field->type == 'address') {
            return Place::query()->find($value)->address ?? null;
        } else {
            return is_scalar($value) ? $value : '';
        }
    }

}
