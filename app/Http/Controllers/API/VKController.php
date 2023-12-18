<?php

namespace App\Http\Controllers\API;

use App\Actions\VK\VKRequestHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VKController extends Controller
{
    public function __invoke(Request $request, VKRequestHandler $handler) {
        $handler->handle($request);
        return response('ok')->header('Content-Type', 'text/plain');
    }
}
