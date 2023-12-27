<?php

namespace App\Http\Controllers;

use alxmsl\Odnoklassniki\API\Client;
use alxmsl\Odnoklassniki\OAuth2\Response\Token;
use App\Services\OKService;
use Illuminate\Support\Facades\Http;


class TestController extends Controller
{
    public function __invoke(): void
    {
        $token = 'tkn2MrrhjyPjIQs9xEliDgJiHVlWvHV39vyjfrhYozWmPjPbwkoOjyWoFlDvEYfnU7uEv0UDF4tNR1FUtWPujLb:CEGHDILGDIHBABABA';
//        $token = 'tkn2UjM9iVAdptg3d16fu2i2Js8E54CzrLnNOgGHJTbZOgWpl9mehdIXuHnFWc3jqvTfj6NvOKJUgrBI7uHcA16:CLFDCKLGDIHBABABA';
//        $response = Http::get("https://api.ok.ru/graph/me/subscriptions?access_token=$token");
//        $response = Http::get("https://api.ok.ru/graph/me/chats?access_token=$token");
        $response = Http::get("https://api.ok.ru/graph/me/chat/chat:C6ae3a0fa6200?access_token=$token");
        $group_id = OKService::getGroupIdByChatID('C6ae3a0fa6200', $token);
        dd($group_id);
    }
}
