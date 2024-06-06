<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WPService
{
    private $photos = [];
    private $videos = [];

    private $domain;

    private $token;

    public function __construct($domain, $token)
    {
        if(!str_starts_with($domain, 'http')) {
            $domain = 'https://' . $domain;
        }
        $this->domain = $domain;
        $this->token = $token;
    }

    public function post($text, $data, $media)
    {
        $response = Http::withToken($this->token)->timeout(60)->post($this->domain . '?rest_route=/api/v1/post', [
            'title' => @$data->product ?: Str::before($text, "\n"),
            'text' => $text,
            'media' => $media
        ]);

        Log::info('ответ от WP', ['response' => $response->json()]);

        $json = $response->json();

        if(!$response) {
            throw new \Exception('WP сайт не отвечает');
        } elseif($response->status() != 200){
            throw new \Exception('Ошибка с кодом ' . $response->status() . ' ' . ($json['message'] ?? ''));
        } elseif(!$json) {
            throw new \Exception('Некорректный ответ от WP');
        } elseif(!($id = $json['post_id'])) {
            throw new \Exception('Нет id поста в ответе от WP');
        }

        return  "{$this->domain}?p=$id";
    }

    public function addPhoto($path)
    {

    }

    public function addVideo($path)
    {

    }
}
