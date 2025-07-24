<?php

namespace App\Http\Controllers;

use alxmsl\Odnoklassniki\API\Client;
use alxmsl\Odnoklassniki\OAuth2\Response\Token;
use App\Services\GigaChatService;
use App\Services\OKService;
use App\Services\OrdVkService;
use Illuminate\Support\Facades\Http;


class TestController extends Controller
{
    public function __invoke(OrdVkService $ord): void
    {
        $ord->getCreatives();
    }
}
