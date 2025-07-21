<?php

namespace App\Http\Controllers;

use alxmsl\Odnoklassniki\API\Client;
use alxmsl\Odnoklassniki\OAuth2\Response\Token;
use App\Services\GigaChatService;
use App\Services\OKService;
use Illuminate\Support\Facades\Http;


class TestController extends Controller
{
    public function __invoke(GigaChatService $giga): void
    {
        $image = $giga->getImage('1f907459-def2-4c57-b2e0-ceaa8d592808');

    }
}
