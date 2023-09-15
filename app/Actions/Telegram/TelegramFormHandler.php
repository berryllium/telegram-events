<?php

namespace App\Actions\Telegram;

use App\Models\Field;
use App\Models\Place;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use TelegramBot\Api\BotApi;

class TelegramFormHandler
{
    public function __construct()
    {
        $this->client = new BotApi('6693099766:AAF45rcSrSzvUapg7IUpazpkIKhUABbUwho');
    }

    public function handle(Request $request, TelegramBot $telegramBot) {
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
        return response()->json(['message_id' => $message->id]);
    }

    private function prepareField(Field $field, string $value)
    {
        if($field->type == 'string' || $field->type == 'number') {
            return $value;
        } elseif($field->type == 'place') {
            return Place::query()->find($value)->name ?? null;
        } elseif($field->type == 'address') {
            return Place::query()->find($value)->address ?? null;
        }
        return '';
    }

}
