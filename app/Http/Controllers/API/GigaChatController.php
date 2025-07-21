<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\GigaChatService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GigachatController extends Controller
{
    public function generate(GigaChatService $giga, Request $request)
    {
        try {
            $prompt = trim($request->input('prompt'));
            if (!$prompt) {
                throw new Exception('Введите запрос!');
            }
            $result = $giga->generate($prompt, $request->input('imageCheckbox'));
            return response()->json(['success' => true, 'description' => $result['text'], 'image' => $result['image'], 'image_path' => $result['image_path']]);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function refreshImage(GigaChatService $giga, Request $request)
    {
        try {
            $prompt = trim($request->input('prompt'));
            if (!$prompt) {
                throw new Exception('Введите запрос!');
            }
            $image_path = $giga->getImage($prompt);
            return response()->json(['success' => true, 'image' => asset(Storage::url($image_path)), 'image_path' => $image_path ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
