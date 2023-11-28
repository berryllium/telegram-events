<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use TelegramBot\Api\Types\InputMedia\InputMediaVideo;

class ImageCompressor
{
    public function compress($path, $path_new = false) {
        try {
            $path_new = $path_new ?: $path;
            $image = Image::make($path);
            if($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->save($path_new);
            }
        } catch (\Exception $exception) {
            Log::error('Can not compress file ' . $path . ' ' . $exception->getMessage());
        }

    }

}
