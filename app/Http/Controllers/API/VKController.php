<?php

namespace App\Http\Controllers\API;

use App\Actions\VK\VKRequestHandler;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Services\VKService;
use Illuminate\Http\Request;

class VKController extends Controller
{
    public function __invoke(Request $request, VKRequestHandler $handler) {
        if($request->get('type') == 'confirmation') {
            $group_id = $request->get('group_id');
            $vk = new VKService(config('app.vk_token'), $group_id, '');
            return response($vk->getGroupConfirmationString())->header('Content-Type', 'text/plain');
        }

        $handler->handle($request);
        return response('ok')->header('Content-Type', 'text/plain');
    }
}
