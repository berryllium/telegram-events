<?php

namespace App\Http\Controllers;

use App\Facades\ImageCompressorFacade;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function __invoke(): void
    {
        $text = <<<STR

салют

🏢 <b>Место: consequatur</b>

📍 <b>Адрес: 848 Stanton MallMoenview, WY 20052</b>

🕒 <b>Часы работы: Ежедневно, 08:00–20:00</b>

ЦенаЖ 	Бесплатно
STR;

        $text = preg_replace("/.*🏢.*[\r\n]+\s?/um", "", $text);
        $text = preg_replace("/.*📍.*[\r\n]+\s?/um", "", $text);
        $text = preg_replace("/.*🕒.*[\r\n]+\s?/um", "", $text);

        dd($text);

    }
}
