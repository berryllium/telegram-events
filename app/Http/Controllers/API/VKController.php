<?php

namespace App\Http\Controllers\API;

use App\Actions\VK\VKRequestHandler;
use App\Http\Controllers\Controller;
use App\Services\VKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VKController extends Controller
{
    public function __invoke(Request $request, VKRequestHandler $handler) {
        if($request->get('type') == 'confirmation') {
            Log::info('vk-webhook-confirmation', $request->toArray());
            $group_id = $request->get('group_id');
            $vk = new VKService(config('app.vk_token'), $group_id, '');
            $code = $vk->getGroupConfirmationString() ?: config('app.vk_confirmation_code');
            return response($code)->header('Content-Type', 'text/plain');
        }

        $handler->handle($request);
        return response('ok')->header('Content-Type', 'text/plain');
    }
}
