<?php

namespace App\Services;

use alxmsl\Odnoklassniki\API\Client;
use alxmsl\Odnoklassniki\API\Response\Error;
use alxmsl\Odnoklassniki\OAuth2\Response\Token;
use Illuminate\Support\Facades\Http;

class OKService
{
    private $client;
    private $gid;

    private $photos = [];
    private $videos = [];

    public function __construct($gid)
    {
        $this->gid = $gid;
        $token = new Token();
        $token->setAccessToken(env('OK_PERMANENT'))
            ->setTokenType(Token::TYPE_SESSION);

        $this->client = new Client();
        $this->client->setApplicationKey(env('OK_PUB_KEY'))
            ->setToken($token)
            ->setClientId(env('OK_APP_ID'))
            ->setClientSecret(env('OK_SECRET'));
    }

    public function uploadPhotos() {
        $uploadParams = $this->call('photosV2.getUploadUrl', ['gid' => $this->gid, 'count' => count($this->photos)]);

        $response = Http::withHeaders([
            "Content-Type:multipart/form-data"
        ]);

        $count = 0;
        foreach ($uploadParams->photo_ids as $photo_id) {
            $fileName = 'photo_' . time() . rand(100, 999);
            $response->attach($fileName, file_get_contents($this->photos[$count++]), $fileName);
        }

        $info = $response->post($uploadParams->upload_url)->json();

        if(!isset($info['photos'])) {
            throw new \Exception(is_object($info) ? $info->message : 'OK: sending photo error');
        }

        $result = [];
        foreach ($info['photos'] as $photo) {
            $result[] = [
                'id' => $photo['token']
            ];
        }

        return $result;
    }

    private function uploadVideos() {
        $uploadParams = $this->call('video.getUploadUrl', ['gid' => $this->gid, 'count' => count($this->photos)]);

        $response = Http::withHeaders([
            "Content-Type:multipart/form-data"
        ]);

        $count = 0;

        if(!isset($uploadParams->photo_ids)) {
            throw new \Exception(get_class($uploadParams));
        }

        foreach ($uploadParams->photo_ids as $photo_id) {
            $fileName = 'photo_' . time() . rand(100, 999);
            $response->attach($fileName, file_get_contents($this->photos[$count++]), $fileName);
        }

        $info = $response->post($uploadParams->upload_url)->json();

        if(!isset($info['photos'])) {
            throw new \Exception(is_object($info) ? $info->message : 'OK: sending photo error');
        }

        $result = [];
        foreach ($info['photos'] as $photo) {
            $result[] = [
                'id' => $photo['token']
            ];
        }

        return $result;
    }

    public function addPhoto(string $path): void
    {
        $this->photos[] = $path;
    }

    public function addVideo(string $path): void
    {
        $this->videos[] = $path;
    }

    public function post($text)
    {
        $data['media'][] = [
            'type' => 'text',
            'text' => $text,
        ];

        if($this->photos) {
            $data['media'][] = [
                'type' => 'photo',
                'list' => $this->uploadPhotos()
            ];
        }

        if($this->videos) {
            $data['media'][] = [
                'type' => 'photo',
                'list' => $this->uploadVideos()
            ];
        }

        $response = $this->call('mediatopic.post', [
            'gid' => $this->gid,
            'attachment' => json_encode($data),
            'type' => 'GROUP_THEME',
        ]);

        if(gettype($response) === 'string') {
            $post_id = (int) $response;
        } else {
            throw new \Exception(is_object($response) ? $response->getMessage() : 'OK: Unknown error');
        }

        return "https://ok.ru/group/{$this->gid}/topic/{$post_id}";

    }

    private function call($method, $params) {
        $response = $this->client->call($method, $params);
        if($response instanceof Error) {
            throw new \Exception($response->getMessage());
        } elseif(!$response) {
            throw new \Exception('OK: Can not run method ' . $method);
        }
        return $response;
    }

}
