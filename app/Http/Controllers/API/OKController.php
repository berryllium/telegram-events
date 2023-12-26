<?php

namespace App\Http\Controllers\API;

use App\Actions\OK\OKRequestHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OKController extends Controller
{
    public function __invoke(Request $request, OKRequestHandler $handler) {
        try {
            Log::info('new message ok', $request->toArray());
        } catch (\Exception $exception) {
            Log::error('noup ok - ' . $exception->getMessage());
        }

        return response('ok')->header('Content-Type', 'text/plain');
    }
}
