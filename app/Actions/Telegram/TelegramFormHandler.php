<?php

namespace App\Actions\Telegram;

use App\Facades\TechBotFacade;
use App\Models\Field;
use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class TelegramFormHandler
{
    public function handle(Request $request, TelegramBot $telegramBot) {
        try {
            $data = $request->toArray();
            $fields = [];

            foreach ($telegramBot->form->fields as $field) {
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
        } catch (\Exception $exception) {
            TechBotFacade::send($exception->getMessage());
        }

        return response()->json(['message_id' => $message->id]);
    }

    private function prepareField(Field $field, string $value)
    {
        if(in_array($field->type,['string', 'number', 'text'])) {
            return $value;
        } elseif($field->type == 'place') {
            return Place::query()->find($value)->name ?? null;
        } elseif($field->type == 'address') {
            return Place::query()->find($value)->address ?? null;
        }
        return '';
    }

}
