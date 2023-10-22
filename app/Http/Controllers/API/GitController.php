<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if($repository = $request->get('repository')) {
            if($repository['name'] == 'telegram-events') {
                $base_path = base_path();
                $result = shell_exec("cd $base_path && git pull origin main");
                Log::info('git pull: ' . $result);
                file_put_contents($base_path . '/git_update', 'need update');
                return response()->json(['status' => 'ok', 'message' => $result]);
            }
        }
        return response()->json(['error' => 'repo not found'], 404);
    }
}
