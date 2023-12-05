<?php

namespace App\Http\Controllers;

use alxmsl\Odnoklassniki\API\Client;
use alxmsl\Odnoklassniki\OAuth2\Response\Token;
use Illuminate\Support\Facades\Http;


class TestController extends Controller
{
    public function __invoke(): void
    {
        phpinfo();
        $Token = new Token();
        $Token->setAccessToken(env('OK_PERMANENT'))
            ->setTokenType(Token::TYPE_SESSION);

        $Client = new Client();
        $Client->setApplicationKey(env('OK_PUB_KEY'))
            ->setToken($Token)
            ->setClientId(env('OK_APP_ID'))
            ->setClientSecret(env('OK_SECRET'));

        $gid = '70000004576336';
        $res = $Client->call('photosV2.getUploadUrl', ['gid' => $gid]);
        $id = $res->photo_ids[0];
        $upload_url = $res->upload_url;

        $response = Http::withHeaders([
            "Content-Type:multipart/form-data"
        ])
            ->attach('file', file_get_contents('/Users/user/PhpstormProjects/freelancer/telegram-events/test.png'), 'pic1')
            ->post($upload_url);

        $content = $response->body();
        $content = json_decode($content, true);

        $id = reset($content['photos'])['token'];



        $data = [
            'media' => [
                [
                    'type' => 'text',
                    'text' => 'this is text'
                ],
                [
                    'type' => 'photo',
                    'list' => [
                        [
                            'id' => $id,
                        ],
                    ]
                ]
            ]
        ];

        $Result = $Client->call('mediatopic.post', [
            'gid' => $gid,
            'attachment' => json_encode($data),
            'type' => 'GROUP_THEME',
        ]);
        dd($Result);
    }
}
