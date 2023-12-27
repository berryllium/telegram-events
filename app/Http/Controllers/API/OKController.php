<?php

namespace App\Http\Controllers\API;

use App\Actions\OK\OKRequestHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OKController extends Controller
{
    public function __invoke(Request $request, OKRequestHandler $handler) {
        $handler->handle($request);
        return response('ok')->header('Content-Type', 'text/plain');
    }
}
