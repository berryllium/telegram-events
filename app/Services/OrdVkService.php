<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OrdVkService {

    private $base_url = 'https://api-sandbox.ord.vk.com';

    public function getCreatives($limit = 5)
    {
        $response = Http::withToken(config('app.vk_ord.auth_key'))->get("{$this->base_url}/v1/creative?limit={$limit}");
        dd($response->json());
    }

    public function addCreative()
    {
        $response = Http::put("{$this->base_url}/v2/creative");
    }
}