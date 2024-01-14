<?php

namespace App\Actions\Telegram;

use App\Facades\ImageCompressorFacade;
use App\Facades\TechBotFacade;
use App\Models\Field;
use App\Models\MessageFile;
use App\Models\Place;
use App\Models\TelegramBot;
use App\Rules\MultibyteLength;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TelegramFormHandler
{
    private $file_paths = [];

    public function handle(Request $request, TelegramBot $telegramBot) {
        try {
            $has_files = false;

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
                    ImageCompressorFacade::compress(Storage::path($path));
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
                $date = $date ? Carbon::parse($date) : Carbon::now();
                $diff = $date->diffInDays(Carbon::now());
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
            $validator = Validator::make(
                ['text' => $text],
                ['text' => new MultibyteLength($max_length)],
                ['text' => __('webapp.limit_error', ['value' => mb_strlen($text) - $max_length])],
            );
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()]);
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
