<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Field;
use App\Models\MessageFile;
use App\Models\Place;
use App\Models\TelegramBot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class TelegramFormHandler
{
    public function handle(Request $request, TelegramBot $telegramBot) {
        try {
            if($request->hasFile('files')) {
                $request->validate(
                    ['files.*' => 'mimes:jpeg,jpg,png,webp,mp4,avi,mkv|max:50000'],
                );
                $files = $request->file('files');
            }
            $data = $request->toArray();
            $fields = [];

            foreach ($telegramBot->form->fields as $field) {
                if($field->code == 'files') continue;
                if(isset($data[$field->code]) && $data[$field->code]) {
                    $fields[$field->code] = $this->prepareField($field, $data[$field->code]);
                } else {
                    $fields[$field->code] = '';
                }
            }


            if(isset($data['price_type']) && $data['price_type']) {
                $fields['price_from'] = $data['price_type'] == 'min' || $data['price_type'] == 'range' ? $fields['price'] : '';
                $fields['price_to'] = $data['price_type'] == 'range' && isset($data['price_to']) ? $data['price_to'] : '';
            }

            if(isset($fields['place']) && $fields['place']) {
                /** @var Place $place */
                $place = $fields['place'];
                $fields['place_address'] = $place->address;
                $fields['place_working_hours'] = $place->working_hours;
                $fields['place_additional_info'] = $place->additional_info;
                $fields['place'] = $place->name;
            }

            if(isset($fields['only_date']) && $fields['only_date'] && $fields['date']) {
                $fields['date'] = Carbon::parse($fields['date'])->format('d.m.Y');
            }

            $text = Blade::render($telegramBot->form->template, $fields);

            $validator = Validator::make(
                ['text' => $text],
                ['text' => 'max:2000'],
                ['text' => __('webapp.limit_error', ['value' => mb_strlen($text) - 2000])],
            );
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()]);
            }

            $message = $telegramBot->messages()->create([
                'data' => json_encode($data),
                'text' => $text,
                'allowed' => false
            ]);

            if(isset($files)) {
                foreach ($files as $file) {
                    $path = $file->store('public/media');
                    $message->message_files()->save(new MessageFile(['filename' => $path]));
                }
            }

        } catch (\Exception $exception) {
            TechBotFacade::send($exception->getMessage() . " bot: {$telegramBot->name}");
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
