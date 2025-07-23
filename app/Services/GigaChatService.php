<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class GigaChatService
{
    public function generate(string $prompt, bool $imageCheckbox = false): array
    {
        $body = [
            'model' => config('app.gigachat.model'),
            'stream' => false,
            'update_interval' => 0,
            'messages' => [
                ['role' => 'system', 'content' => 'Создай описание на основе запроса пользователя длинной не больше 700 символов'],
                ['role' => 'user', 'content' => $prompt],
            ]
        ];

        $response = Http::asJson()
            ->accept('application/json')
            ->withToken($this->getToken())
            ->withHeader('X-Client-ID', config('app.gigachat.client_id'))
            ->withoutVerifying()
            ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions', $body)
            ->json();

        $text = $response['choices'][0]['message']['content'] ?? null;
        if (!$text) {
            $message = 'Giga: ' . 'Generating failed';
            Log::error($message, ['response' => $response]);
            throw new Exception($message);
        }

        Log::info('Gigachat text generation', ['prompt' => $prompt, 'response' => $response]);

        $image_path = $imageCheckbox ? $this->getImage($text) : null;

        return ['text' => $text, 'image' => ($image_path ? asset(Storage::url($image_path)) : null), 'image_path' => $image_path];
    }

    public function generateImage($prompt)
    {
        $body = [
            'model' => config('app.gigachat.model'),
            'stream' => false,
            'update_interval' => 0,
            'function_call' => 'auto',
            'messages' => [
                ['role' => 'system', 'content' => 'Ты художник, нарисуй картинку на основе текста'],
                ['role' => 'user', 'content' => 'Нарисуй иллюистрацию к тексту: ' . $prompt],
            ]
        ];

        $response = Http::asJson()
            ->withToken($this->getToken())
            ->withHeader('X-Client-ID', config('app.gigachat.client_id'))
            ->withoutVerifying()
            ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions', $body)
            ->json();

        $text = $response['choices'][0]['message']['content'] ?? null;

        if(!$text) {
            $message = 'Giga: ' . 'Image generation failed';
            Log::error($message, ['response' => $response, 'body' => $body]);
            throw new Exception($message);
        }

        Log::info('Gigachat image generation', ['prompt' => $prompt, 'response' => $response, 'body' => $body]);

        // extract image id from the response
        preg_match('/<img[^>]+src="([^"]+)"/', $text, $matches);
        $image_id = $matches[1] ?? null;

        if(!$image_id) {
            $message = 'Giga: ' . 'Message ID is not extracted';
            Log::error($message, ['response' => $response, 'body' => $body]);
            throw new Exception($message);
        }

        return $image_id;
    }

    public function getImage($text)
    {
        $image_id = $this->generateImage($text);

        $response = Http::asJson()
            ->withToken($this->getToken())
            ->withHeader('X-Client-ID', config('app.gigachat.client_id'))
            ->withoutVerifying()
            ->get("https://gigachat.devices.sberbank.ru/api/v1/files/{$image_id}/content");

        $path = "public/media/gigachat/images/$image_id.jpg";
        Storage::disk()->put($path, $response->body());
        return $path;
    }

    private function getToken()
    {
        return Cache::remember('gigachat_token', 1200, function () {
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
        });
    }
}
