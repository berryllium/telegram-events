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
        $response = Http::withToken($this->token)->timeout(5)->post($this->domain . '?rest_route=/api/v1/post', [
            'title' => @$data->product ?: Str::before($text, "\n"),
            'text' => $text,
            'media' => $media
        ]);

        $json = $response->json();
        Log::info('ответ от WP', ['response' => $json]);


        if(!$response) {
            throw new \Exception('WP сайт не отвечает ' . $this->domain );
        } elseif($response->status() != 200){
            throw new \Exception('WP ' . $this->domain  . ' Ошибка с кодом ' . $response->status() . ' ' . ($json['error'] ?? ''));
        } elseif(!$json) {
            throw new \Exception('Некорректный ответ от WP ' . $this->domain );
        } elseif(!($id = $json['post_id'])) {
            throw new \Exception('Нет id поста в ответе от WP ' . $this->domain );
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
