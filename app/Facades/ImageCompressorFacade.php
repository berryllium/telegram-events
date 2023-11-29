<?php

namespace App\Facades;

/**
 * @method static string compress(string $path, string $new_path = false)
 */
class ImageCompressorFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'image_compressor.service';
    }
}