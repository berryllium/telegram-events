<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ImageCompressor
{
    public function compress($path, $path_new = false) {
        try {
            $path_new = $path_new ?: $path;
            $image = Image::make($path);
            if($image->width() > 1920) {
                $image->resize(1920, null, function ($constraint) {
                    /** @var Constraint $constraint */
                    $constraint->aspectRatio();
                });
                $image->save($path_new, 80);
            }
            Log::info('File has been compressed ' . $path_new);
        } catch (\Exception $exception) {
            Log::error('Can not compress file ' . $path . ' ' . $exception->getMessage());
        }

    }

}
