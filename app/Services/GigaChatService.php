<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GigaChatService
{
    public function generate(string $prompt): string
    {
        $token = Cache::remember('gigachat_token', 1200, function() {
            return $this->auth();
        });

        $uuid = (string) Str::uuid();

        $body = [
            'model' => config('app.gigachat.model'),
            'stream' => false,
            'update_interval' => 0,
            'messages' => [
                ['role' => 'system', 'content' => 'Создая описание на основе запроса пользователя длинной не больше 700 символов'],
                ['role' => 'user', 'content' => $prompt],
            ]
        ];


        $response = Http::asJson()
            ->accept('application/json')
            ->withToken($token)
            ->withHeader('X-Client-ID', config('app.gigachat.client_id'))
            ->withoutVerifying()
            ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions', $body)
            ->json();

        $text = $response['choices'][0]['message']['content'] ?? null;
        if (!$text) {
            $message = 'Giga: ' . 'Generating failed';
            Log::error($message, ['response' => $response, 'uuid' => $uuid]);
            throw new Exception($message);
        }

        Log::info('Gigachat generation', ['prompt' => $prompt, 'text' => $text]);

        return $text;
    }

    private function auth()
    {
        $uuid = (string) Str::uuid();
        $response = Http::asForm()
            ->withHeader('RqUID', $uuid)
            ->withHeader('Authorization', 'Basic ' . config('app.gigachat.auth_key'))
            ->acceptJson()
            ->withoutVerifying()
            ->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', ['scope' => config('app.gigachat.scope')])
            ->json();

        if (!isset($response['access_token'])) {
            $message = 'Giga: ' . ($response['message'] ?? 'Token request failed');
            Log::error($message, ['response' => $response, 'uuid' => $uuid]);
            throw new Exception($message);
        }

        return $response['access_token'];
    }
}
