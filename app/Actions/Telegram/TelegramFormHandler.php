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

class TelegramFormHandler
{
    public function handle(Request $request, TelegramBot $telegramBot) {
        try {
            if($request->hasFile('files')) {
                $request->validate(
                    ['files.*' => 'mimes:jpeg,jpg,png,webp,mp4,avi,mkv|max:50000'],
                    ['files.*.mimes' => 'Файлы могут быть только форматов: jpeg, jpg, png, webp, mp4, avi, mkv']
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

            $message = $telegramBot->messages()->create([
                'data' => json_encode($data),
                'text' => Blade::render($telegramBot->form->template, $fields),
                'allowed' => false
            ]);

            if(isset($files)) {
                foreach ($files as $file) {
                    $path = $file->store('public/media');
                    $message->message_files()->save(new MessageFile(['filename' => $path]));
                }
            }

        } catch (\Exception $exception) {
            TechBotFacade::send($exception->getMessage());
            return response()->json(['error' => $exception->getMessage()]);
        }

        return response()->json(['message_id' => $message->id]);
    }

    private function prepareField(Field $field, string $value)
    {
        if(in_array($field->type,['string', 'number', 'text', 'radio', 'select'])) {
            return $value;
        } elseif($field->type == 'date') {
            return Carbon::parse($value)->format('d.m.Y H:i:s');
        } elseif($field->type == 'checkbox') {
            return $value ? 'Да' : 'Нет';
        }  elseif($field->type == 'place') {
            return Place::query()->find($value)->name ?? null;
        } elseif($field->type == 'address') {
            return Place::query()->find($value)->address ?? null;
        }
        return '';
    }

}
