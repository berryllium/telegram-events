<?php

namespace App\Http\Controllers;

use App\Facades\ImageCompressorFacade;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function __invoke(): void
    {
        $res = ImageCompressorFacade::compress(Storage::path('public/media/test.jpg'), Storage::path('public/media/test1.jpg'));
        dd($res);
    }
}
