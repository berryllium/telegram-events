<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GigaChatService;
use Exception;
use Illuminate\Http\Request;

class GigachatController extends Controller
{
    public function generate(GigaChatService $giga, Request $request)
    {
        try {
            $prompt = trim($request->input('prompt'));
            if(!$prompt) {
                throw new Exception('Введите запрос!');
            }
            $description = $giga->generate($prompt);
            return response()->json(['success' => true, 'description' => $description]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
